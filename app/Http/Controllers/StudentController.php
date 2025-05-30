<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\CourseSetting;
use App\Models\PaymentMethod;
use App\Models\TimeCondition;
use App\Models\Student;
use App\Models\Application;
use App\Models\ClassSession;
use App\Services\SmsService;
use App\Mail\ApplicationSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class StudentController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Display the landing page with course information
     */
    public function index()
    {
        // Cache course settings for better performance
        $courseSetting = Cache::remember('course_settings', 3600, function () {
            return CourseSetting::first();
        });
        
        $activeBatch = Batch::where('is_active', true)->with('classSessions')->first();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        
        return view('student.landing', compact('courseSetting', 'activeBatch', 'paymentMethods'));
    }

    /**
     * Check if student understands YouTube classes
     */
    public function checkYoutube(Request $request)
    {
        $request->validate([
            'understands' => 'required|in:yes,no'
        ]);
        
        $understands = $request->input('understands');
        
        if ($understands === 'no') {
            $courseSetting = CourseSetting::first();
            return response()->json([
                'youtube_link' => $courseSetting->youtube_link,
                'message' => 'Please watch our YouTube classes first'
            ]);
        }
        
        return response()->json(['continue' => true]);
    }

    /**
     * Get class time based on profession and score
     */
    public function getClassTime(Request $request)
    {
        $request->validate([
            'profession' => 'required|in:student,job_holder,housewife',
            'score' => 'required|integer|min:0|max:40'
        ]);
        
        $profession = $request->input('profession');
        $score = $request->input('score');
        
        // Get active batch
        $activeBatch = Batch::where('is_active', true)->with('classSessions')->first();
        
        if (!$activeBatch) {
            return response()->json(['error' => 'No active batch found'], 404);
        }
        
        $condition = TimeCondition::where('profession', $profession)->first();
        
        if (!$condition) {
            Log::error('Time condition not found for profession: ' . $profession);
            return response()->json(['error' => 'No time condition found'], 404);
        }
        
        $targetTime = '';
        
        if ($condition->is_fixed) {
            $targetTime = $condition->fixed_time;
        } else {
            foreach ($condition->score_rules as $rule) {
                if ($score >= $rule['min_score'] && $score <= $rule['max_score']) {
                    $targetTime = $rule['time'];
                    break;
                }
            }
        }
        
        // Find matching session in active batch
        $matchingSession = $activeBatch->classSessions()
            ->where('time', $targetTime)
            ->first();
        
        if ($matchingSession) {
            return response()->json([
                'time' => $matchingSession->time,
                'days' => $matchingSession->days,
                'session_name' => $matchingSession->session_name,
                'message' => 'Time assigned based on your profile'
            ]);
        }
        
        // If no exact match, get the first available session
        $defaultSession = $activeBatch->classSessions()->first();
        
        if ($defaultSession) {
            return response()->json([
                'time' => $defaultSession->time,
                'days' => $defaultSession->days,
                'session_name' => $defaultSession->session_name,
                'message' => 'Default session assigned'
            ]);
        }
        
        // Fallback if no sessions exist
        return response()->json([
            'time' => 'Evening 7:00 PM',
            'days' => 'Sunday, Tuesday, Thursday',
            'session_name' => 'General Session',
            'message' => 'Default time assigned'
        ]);
    }

    /**
     * Submit new application
     */
    public function submitApplication(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20|regex:/^[0-9+\-\s]+$/',
            'course_type' => 'required|in:academic,gt',
            'profession' => 'required|in:student,job_holder,housewife',
            'score' => 'required|integer|min:0|max:40',
            'payment_method' => 'required|string',
            'payment_id' => 'required|string|max:255',
            'screenshot' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Check for duplicate applications
        $existingApplication = Application::whereHas('student', function($query) use ($validated) {
            $query->where('email', $validated['email'])
                  ->orWhere('phone', $validated['phone']);
        })->where('status', '!=', 'rejected')
          ->exists();

        if ($existingApplication) {
            return response()->json([
                'error' => 'You already have an active application. Please contact support if you need assistance.'
            ], 400);
        }

        DB::beginTransaction();
        
        try {
            // Get active batch
            $activeBatch = Batch::where('is_active', true)->with('classSessions')->first();
            
            if (!$activeBatch || $activeBatch->status !== 'open') {
                return response()->json([
                    'error' => 'No active batch available. Please check back later.'
                ], 400);
            }

            // Determine class session based on profession and score
            $sessionInfo = $this->determineClassSession($validated['profession'], $validated['score'], $activeBatch);
            
            // Create student
            $student = Student::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'course_type' => $validated['course_type'],
                'profession' => $validated['profession'],
                'score' => $validated['score'],
                'class_session_id' => $sessionInfo['session_id']
            ]);

            // Handle screenshot upload
            $screenshotPath = null;
            if ($request->hasFile('screenshot')) {
                $file = $request->file('screenshot');
                $fileName = time() . '_' . $student->id . '.' . $file->getClientOriginalExtension();
                $screenshotPath = $file->storeAs('payment_screenshots', $fileName, 'public');
            }

            // Create application
            $application = Application::create([
                'student_id' => $student->id,
                'batch_id' => $activeBatch->id,
                'payment_method' => $validated['payment_method'],
                'payment_id' => $validated['payment_id'],
                'screenshot' => $screenshotPath,
                'status' => 'pending'
            ]);

            // Update session count
            ClassSession::where('id', $sessionInfo['session_id'])->increment('current_count');

            // Send confirmation email
            $this->sendApplicationEmail($student, $application);

            DB::commit();

            // Log successful application
            Log::info('New application submitted', [
                'application_id' => $application->id,
                'student_email' => $student->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'আপনার আবেদন সফলভাবে জমা হয়েছে!',
                'data' => [
                    'application_id' => $application->id,
                    'name' => $student->name,
                    'batch' => $activeBatch->name,
                    'class_time' => $sessionInfo['time'],
                    'class_days' => $sessionInfo['days'],
                    'payment_id' => $application->payment_id,
                    'message' => 'আপনি ২৪ ঘন্টার মধ্যে confirmation পাবেন।'
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Application submission error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Delete uploaded file if exists
            if (isset($screenshotPath) && \Storage::disk('public')->exists($screenshotPath)) {
                \Storage::disk('public')->delete($screenshotPath);
            }
            
            return response()->json([
                'error' => 'দুঃখিত, কিছু সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।'
            ], 500);
        }
    }

    /**
     * Determine class session based on profession and score
     */
    private function determineClassSession($profession, $score, $activeBatch)
    {
        $condition = TimeCondition::where('profession', $profession)->first();
        
        $targetTime = '';
        
        if ($condition) {
            if ($condition->is_fixed) {
                $targetTime = $condition->fixed_time;
            } else {
                foreach ($condition->score_rules as $rule) {
                    if ($score >= $rule['min_score'] && $score <= $rule['max_score']) {
                        $targetTime = $rule['time'];
                        break;
                    }
                }
            }
        }
        
        // Find matching session or create one
        $session = $activeBatch->classSessions()
            ->where('time', $targetTime)
            ->first();
        
        if (!$session) {
            // Get first available session as fallback
            $session = $activeBatch->classSessions()->first();
        }
        
        if (!$session) {
            // Create a default session if none exists
            $session = $activeBatch->classSessions()->create([
                'session_name' => $this->getSessionName($profession),
                'time' => $targetTime ?: 'Evening 7:00 PM',
                'days' => $this->getClassDays($profession)
            ]);
        }
        
        return [
            'session_id' => $session->id,
            'time' => $session->time,
            'days' => $session->days,
            'session_name' => $session->session_name
        ];
    }

    /**
     * Get session name based on profession
     */
    private function getSessionName($profession)
    {
        $names = [
            'student' => 'Student Morning Session',
            'job_holder' => 'Professional Evening Session',
            'housewife' => 'Housewife Morning Session'
        ];
        
        return $names[$profession] ?? 'General Session';
    }

    /**
     * Get class days based on profession
     */
    private function getClassDays($profession)
    {
        $days = [
            'student' => 'Sunday, Tuesday, Thursday',
            'job_holder' => 'Sunday, Tuesday, Thursday',
            'housewife' => 'Monday, Wednesday, Friday'
        ];
        
        return $days[$profession] ?? 'Sunday, Tuesday, Thursday';
    }

    /**
     * Send application confirmation email
     */
    private function sendApplicationEmail($student, $application)
    {
        try {
            $courseSetting = CourseSetting::first();
            
            Mail::to($student->email)->send(new ApplicationSubmitted($student, $application, $courseSetting));
            
            Log::info('Application email sent', [
                'student_email' => $student->email,
                'application_id' => $application->id
            ]);
        } catch (\Exception $e) {
            Log::error('Email sending failed', [
                'error' => $e->getMessage(),
                'student_email' => $student->email
            ]);
        }
    }

    /**
     * Check application status (for future use)
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'application_id' => 'required|integer',
            'email' => 'required|email'
        ]);

        $application = Application::whereHas('student', function($query) use ($request) {
            $query->where('email', $request->email);
        })->where('id', $request->application_id)->first();

        if (!$application) {
            return response()->json([
                'error' => 'Application not found'
            ], 404);
        }

        return response()->json([
            'status' => $application->status,
            'batch' => $application->batch->name,
            'class_time' => $application->student->classSession->time ?? 'N/A',
            'class_days' => $application->student->classSession->days ?? 'N/A',
            'message' => $this->getStatusMessage($application->status)
        ]);
    }

    /**
     * Get status message
     */
    private function getStatusMessage($status)
    {
        $messages = [
            'pending' => 'আপনার আবেদন যাচাই করা হচ্ছে। অনুগ্রহ করে অপেক্ষা করুন।',
            'approved' => 'অভিনন্দন! আপনার আবেদন অনুমোদিত হয়েছে।',
            'rejected' => 'দুঃখিত, আপনার আবেদন গ্রহণযোগ্য হয়নি।'
        ];

        return $messages[$status] ?? 'Unknown status';
    }
}