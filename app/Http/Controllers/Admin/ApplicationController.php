<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CourseSetting;
use App\Models\Batch;
use App\Services\SmsService;
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
                'approved_by' => auth()->guard('admin')->user()->email
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Application approved successfully! Notifications sent to the student.');
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Application approval failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Failed to approve application. Please try again.');
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
                'rejected_by' => auth()->guard('admin')->user()->email
            ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Application rejected. Notification sent to the student.');
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Application rejection failed', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Failed to reject application. Please try again.');
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
                        $this->approve($application);
                        $processed++;
                        break;
                        
                    case 'reject':
                        $this->reject(new Request(['rejection_reason' => 'Bulk rejection']), $application);
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
                    $app->student->name,
                    $app->student->email,
                    $app->student->phone,
                    strtoupper($app->student->course_type),
                    ucfirst(str_replace('_', ' ', $app->student->profession)),
                    $app->student->score . '/40',
                    $app->batch->name,
                    $app->student->classSession->time ?? 'N/A',
                    $app->student->classSession->days ?? 'N/A',
                    $app->payment_method,
                    $app->payment_id,
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
            Mail::to($student->email)->send(new ApplicationApproved($student, $application, $courseSetting));
            
            Log::info('Approval email sent', [
                'student_email' => $student->email,
                'application_id' => $application->id
            ]);
        } catch (\Exception $e) {
            Log::error('Approval email failed', [
                'error' => $e->getMessage(),
                'student_email' => $student->email
            ]);
            // Don't throw exception, continue with process
        }
    }

    /**
     * Send approval SMS
     */
    private function sendApprovalSms($student, $application, $courseSetting)
    {
        try {
            $message = "✅ অভিনন্দন {$student->name}!\n";
            $message .= "আপনার IELTS কোর্স এডমিশন কনফার্ম হয়েছে।\n";
            $message .= "📚 Batch: {$application->batch->name}\n";
            $message .= "⏰ Time: {$student->classSession->time}\n";
            $message .= "📅 Days: {$student->classSession->days}\n";
            $message .= "🚀 Class starts: {$application->batch->start_date->format('d M Y')}\n";
            $message .= "📱 Contact: {$courseSetting->contact_number}";
            
            $this->smsService->send($student->phone, $message);
            
            Log::info('Approval SMS sent', [
                'student_phone' => $student->phone,
                'application_id' => $application->id
            ]);
        } catch (\Exception $e) {
            Log::error('Approval SMS failed', [
                'error' => $e->getMessage(),
                'student_phone' => $student->phone
            ]);
        }
    }

    /**
     * Send rejection email
     */
    private function sendRejectionEmail($student, $application, $courseSetting)
    {
        try {
            Mail::to($student->email)->send(new ApplicationRejected($student, $application, $courseSetting));
            
            Log::info('Rejection email sent', [
                'student_email' => $student->email,
                'application_id' => $application->id
            ]);
        } catch (\Exception $e) {
            Log::error('Rejection email failed', [
                'error' => $e->getMessage(),
                'student_email' => $student->email
            ]);
        }
    }

    /**
     * Send rejection SMS
     */
    private function sendRejectionSms($student, $application)
    {
        try {
            $message = "প্রিয় {$student->name},\n";
            $message .= "দুঃখিত, আপনার IELTS কোর্স আবেদন গ্রহণযোগ্য হয়নি।\n";
            if ($application->rejection_reason) {
                $message .= "কারণ: {$application->rejection_reason}\n";
            }
            $message .= "বিস্তারিত জানতে ইমেইল চেক করুন।";
            
            $this->smsService->send($student->phone, $message);
            
            Log::info('Rejection SMS sent', [
                'student_phone' => $student->phone,
                'application_id' => $application->id
            ]);
        } catch (\Exception $e) {
            Log::error('Rejection SMS failed', [
                'error' => $e->getMessage(),
                'student_phone' => $student->phone
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

        $student = $application->student;
        $courseSetting = CourseSetting::first();

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
            
            return redirect()->back()->with('error', 'Failed to resend notification.');
        }
    }
}