<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CourseSetting;
use App\Models\Batch;
use App\Services\SmsService;
use App\Helpers\SmsTemplates;
use App\Mail\ApplicationApproved;
use App\Mail\ApplicationRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApplicationController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Display applications with advanced filtering
     */
    public function index(Request $request)
    {
        $query = Application::with(['student', 'student.classSession', 'batch']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($studentQuery) use ($search) {
                    $studentQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })
                ->orWhere('payment_id', 'like', "%{$search}%")
                ->orWhere('id', $search);
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by batch
        if ($request->has('batch_id') && $request->batch_id != 'all') {
            $query->where('batch_id', $request->batch_id);
        }

        // Filter by profession
        if ($request->has('profession') && $request->profession != 'all') {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('profession', $request->profession);
            });
        }

        // Filter by course type
        if ($request->has('course_type') && $request->course_type != 'all') {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('course_type', $request->course_type);
            });
        }

        // Date range filter
        if ($request->has('from_date') && !empty($request->from_date)) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date') && !empty($request->to_date)) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Get applications with pagination
        $applications = $query->paginate($request->get('per_page', 20))->withQueryString();
        
        // Get data for filters
        $batches = Batch::orderBy('created_at', 'desc')->get();
        
        // Get statistics
        $stats = $this->getApplicationStats();
        
        return view('admin.applications', compact('applications', 'batches', 'stats'));
    }

    /**
     * Show single application details
     */
    public function show(Application $application)
    {
        $application->load(['student', 'student.classSession', 'batch']);
        $courseSetting = CourseSetting::first();
        
        // Get similar applications (same batch, profession, or score range)
        $similarApplications = Application::with(['student'])
            ->where('id', '!=', $application->id)
            ->where(function($query) use ($application) {
                $query->where('batch_id', $application->batch_id)
                    ->orWhereHas('student', function($q) use ($application) {
                        $q->where('profession', $application->student->profession)
                          ->orWhereBetween('score', [
                              $application->student->score - 5,
                              $application->student->score + 5
                          ]);
                    });
            })
            ->limit(5)
            ->get();
        
        return view('admin.application-details', compact('application', 'courseSetting', 'similarApplications'));
    }

    /**
     * Approve application with notifications
     */
    public function approve(Application $application)
    {
        // Check if already processed
        if ($application->status !== 'pending') {
            return redirect()->back()->with('error', 'This application has already been processed.');
        }

        DB::beginTransaction();
        
        try {
            // Update application status
            $application->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->guard('admin')->id()
            ]);
            
            // Load relationships
            $application->load(['student.classSession', 'batch']);
            
            $student = $application->student;
            $courseSetting = CourseSetting::first();
            
            // Send approval email
            $this->sendApprovalEmail($student, $application, $courseSetting);
            
            // Send SMS notification
            $this->sendApprovalSms($student, $application, $courseSetting);
            
            // Log the approval
            Log::info('Application approved', [
                'application_id' => $application->id,
                'student_id' => $student->id,
                'approved_by' => auth()->guard('admin')->user()->email ?? 'Unknown'
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Application approved successfully! Notifications sent to the student.');
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Application approval failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Failed to approve application: ' . $e->getMessage());
        }
    }

    /**
     * Reject application with reason
     */
    public function reject(Request $request, Application $application)
    {
        // Validate rejection reason
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        // Check if already processed
        if ($application->status !== 'pending') {
            return redirect()->back()->with('error', 'This application has already been processed.');
        }

        DB::beginTransaction();
        
        try {
            // Update application status
            $application->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'rejected_at' => now(),
                'rejected_by' => auth()->guard('admin')->id()
            ]);
            
            // Load relationships
            $application->load(['student', 'batch']);
            
            $student = $application->student;
            $courseSetting = CourseSetting::first();
            
            // Send rejection email
            $this->sendRejectionEmail($student, $application, $courseSetting);
            
            // Send SMS notification
            $this->sendRejectionSms($student, $application);
            
            // Log the rejection
            Log::info('Application rejected', [
                'application_id' => $application->id,
                'student_id' => $student->id,
                'reason' => $request->rejection_reason,
                'rejected_by' => auth()->guard('admin')->user()->email ?? 'Unknown'
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Application rejected. Notification sent to the student.');
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Application rejection failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Failed to reject application: ' . $e->getMessage());
        }
    }

    /**
     * Bulk action on multiple applications
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'applications' => 'required|array|min:1',
            'applications.*' => 'exists:applications,id',
            'action' => 'required|in:approve,reject,delete'
        ]);

        $applications = Application::whereIn('id', $request->applications)
            ->where('status', 'pending')
            ->get();

        $processed = 0;
        $failed = 0;

        foreach ($applications as $application) {
            try {
                switch ($request->action) {
                    case 'approve':
                        // Direct approve without calling the full approve method to avoid redirect
                        $application->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                            'approved_by' => auth()->guard('admin')->id()
                        ]);
                        
                        $application->load(['student.classSession', 'batch']);
                        $courseSetting = CourseSetting::first();
                        
                        $this->sendApprovalEmail($application->student, $application, $courseSetting);
                        $this->sendApprovalSms($application->student, $application, $courseSetting);
                        
                        $processed++;
                        break;
                        
                    case 'reject':
                        $application->update([
                            'status' => 'rejected',
                            'rejection_reason' => 'Bulk rejection',
                            'rejected_at' => now(),
                            'rejected_by' => auth()->guard('admin')->id()
                        ]);
                        
                        $application->load(['student', 'batch']);
                        $courseSetting = CourseSetting::first();
                        
                        $this->sendRejectionEmail($application->student, $application, $courseSetting);
                        $this->sendRejectionSms($application->student, $application);
                        
                        $processed++;
                        break;
                        
                    case 'delete':
                        $application->delete();
                        $processed++;
                        break;
                }
            } catch (\Exception $e) {
                $failed++;
                Log::error('Bulk action failed for application', [
                    'application_id' => $application->id,
                    'action' => $request->action,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $message = "Bulk action completed. Processed: {$processed}";
        if ($failed > 0) {
            $message .= ", Failed: {$failed}";
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Export applications to CSV
     */
    public function export(Request $request)
    {
        // Apply same filters as index method
        $query = Application::with(['student', 'student.classSession', 'batch']);

        // Apply filters (same as index method)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($studentQuery) use ($search) {
                    $studentQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })
                ->orWhere('payment_id', 'like', "%{$search}%")
                ->orWhere('id', $search);
            });
        }

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('batch_id') && $request->batch_id != 'all') {
            $query->where('batch_id', $request->batch_id);
        }

        $applications = $query->get();
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="applications_' . date('Y-m-d_H-i-s') . '.csv"',
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'Application ID',
                'Name',
                'Email',
                'Phone',
                'Course Type',
                'Profession',
                'Score',
                'Batch',
                'Session Time',
                'Class Days',
                'Payment Method',
                'Payment ID',
                'Status',
                'Applied At',
                'Approved/Rejected At',
                'Rejection Reason'
            ]);

            foreach ($applications as $app) {
                fputcsv($file, [
                    $app->id,
                    $app->student->name ?? 'N/A',
                    $app->student->email ?? 'N/A',
                    $app->student->phone ?? 'N/A',
                    strtoupper($app->student->course_type ?? 'N/A'),
                    ucfirst(str_replace('_', ' ', $app->student->profession ?? 'N/A')),
                    ($app->student->score ?? 0) . '/40',
                    $app->batch->name ?? 'N/A',
                    $app->student->classSession->time ?? 'N/A',
                    $app->student->classSession->days ?? 'N/A',
                    $app->payment_method ?? 'N/A',
                    $app->payment_id ?? 'N/A',
                    ucfirst($app->status),
                    $app->created_at->format('Y-m-d H:i:s'),
                    $app->approved_at ?? $app->rejected_at ?? 'N/A',
                    $app->rejection_reason ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get application statistics
     */
    private function getApplicationStats()
    {
        $stats = [
            'total' => Application::count(),
            'pending' => Application::where('status', 'pending')->count(),
            'approved' => Application::where('status', 'approved')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
            'today' => Application::whereDate('created_at', today())->count(),
            'this_week' => Application::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'this_month' => Application::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return $stats;
    }

    /**
     * Send approval email
     */
    private function sendApprovalEmail($student, $application, $courseSetting)
    {
        try {
            if (!$student || !$student->email) {
                Log::warning('Cannot send approval email - student email not found', [
                    'application_id' => $application->id
                ]);
                return;
            }

            Mail::to($student->email)->send(new ApplicationApproved($student, $application, $courseSetting));
            
            Log::info('Approval email sent', [
                'student_email' => $student->email,
                'application_id' => $application->id
            ]);
        } catch (\Exception $e) {
            Log::error('Approval email failed', [
                'error' => $e->getMessage(),
                'student_email' => $student->email ?? 'Unknown'
            ]);
            // Don't throw exception, continue with process
        }
    }

    /**
     * Send approval SMS using SmsTemplates
     */
    private function sendApprovalSms($student, $application, $courseSetting)
    {
        try {
            if (!$student || !$student->phone) {
                Log::warning('Cannot send approval SMS - student phone not found', [
                    'application_id' => $application->id
                ]);
                return;
            }

            // Check if SmsTemplates class exists
            if (class_exists('App\Helpers\SmsTemplates')) {
                $message = SmsTemplates::applicationApproved($student, $application, $courseSetting);
            } else {
                // Fallback to inline message
                $message = "✅ অভিনন্দন {$student->name}!\n";
                $message .= "আপনার IELTS কোর্স এডমিশন কনফার্ম হয়েছে।\n";
                $message .= "📚 Batch: " . ($application->batch->name ?? 'N/A') . "\n";
                $message .= "⏰ Time: " . ($student->classSession->time ?? 'N/A') . "\n";
                $message .= "📅 Days: " . ($student->classSession->days ?? 'N/A') . "\n";
                $message .= "🚀 Class starts: " . ($application->batch->start_date ? $application->batch->start_date->format('d M Y') : 'N/A') . "\n";
                $message .= "📱 Contact: " . ($courseSetting->contact_number ?? 'N/A');
            }
            
            $this->smsService->send($student->phone, $message);
            
            // Optional: Send welcome message after approval
            if (class_exists('App\Helpers\SmsTemplates') && method_exists(SmsTemplates::class, 'welcomeMessage')) {
                $welcomeMessage = SmsTemplates::welcomeMessage($student);
                $this->smsService->send($student->phone, $welcomeMessage);
            }
            
            Log::info('Approval SMS sent', [
                'student_phone' => $student->phone,
                'application_id' => $application->id
            ]);
        } catch (\Exception $e) {
            Log::error('Approval SMS failed', [
                'error' => $e->getMessage(),
                'student_phone' => $student->phone ?? 'Unknown'
            ]);
        }
    }

    /**
     * Send rejection email
     */
    private function sendRejectionEmail($student, $application, $courseSetting)
    {
        try {
            if (!$student || !$student->email) {
                Log::warning('Cannot send rejection email - student email not found', [
                    'application_id' => $application->id
                ]);
                return;
            }

            Mail::to($student->email)->send(new ApplicationRejected($student, $application, $courseSetting));
            
            Log::info('Rejection email sent', [
                'student_email' => $student->email,
                'application_id' => $application->id
            ]);
        } catch (\Exception $e) {
            Log::error('Rejection email failed', [
                'error' => $e->getMessage(),
                'student_email' => $student->email ?? 'Unknown'
            ]);
        }
    }

    /**
     * Send rejection SMS using SmsTemplates
     */
    private function sendRejectionSms($student, $application)
    {
        try {
            if (!$student || !$student->phone) {
                Log::warning('Cannot send rejection SMS - student phone not found', [
                    'application_id' => $application->id
                ]);
                return;
            }

            // Check if SmsTemplates class exists
            if (class_exists('App\Helpers\SmsTemplates')) {
                $message = SmsTemplates::applicationRejected($student, $application);
            } else {
                // Fallback to inline message
                $message = "প্রিয় {$student->name},\n";
                $message .= "দুঃখিত, আপনার IELTS কোর্স আবেদন গ্রহণযোগ্য হয়নি।\n";
                if ($application->rejection_reason) {
                    $message .= "কারণ: {$application->rejection_reason}\n";
                }
                $message .= "বিস্তারিত জানতে ইমেইল চেক করুন।";
            }
            
            $this->smsService->send($student->phone, $message);
            
            Log::info('Rejection SMS sent', [
                'student_phone' => $student->phone,
                'application_id' => $application->id
            ]);
        } catch (\Exception $e) {
            Log::error('Rejection SMS failed', [
                'error' => $e->getMessage(),
                'student_phone' => $student->phone ?? 'Unknown'
            ]);
        }
    }

    /**
     * Resend notification
     */
    public function resendNotification(Application $application)
    {
        if ($application->status === 'pending') {
            return redirect()->back()->with('error', 'Cannot send notification for pending application.');
        }

        // Load relationships
        $application->load(['student.classSession', 'batch']);
        
        $student = $application->student;
        $courseSetting = CourseSetting::first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student information not found.');
        }

        try {
            if ($application->status === 'approved') {
                $this->sendApprovalEmail($student, $application, $courseSetting);
                $this->sendApprovalSms($student, $application, $courseSetting);
            } else {
                $this->sendRejectionEmail($student, $application, $courseSetting);
                $this->sendRejectionSms($student, $application);
            }

            return redirect()->back()->with('success', 'Notification resent successfully!');
        } catch (\Exception $e) {
            Log::error('Resend notification failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Failed to resend notification: ' . $e->getMessage());
        }
    }
}