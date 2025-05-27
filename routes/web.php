<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseSettingController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\TimeConditionController;
use App\Services\SmsService;

// Student Routes
Route::get('/', [StudentController::class, 'index'])->name('home');
Route::post('/check-youtube', [StudentController::class, 'checkYoutube'])->name('check.youtube');
Route::post('/get-class-time', [StudentController::class, 'getClassTime'])->name('get.class.time');
Route::post('/submit-application', [StudentController::class, 'submitApplication'])->name('submit.application');

// Admin Auth Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Course Settings
        Route::get('/course-settings', [CourseSettingController::class, 'index'])->name('admin.course.settings');
        Route::put('/course-settings', [CourseSettingController::class, 'update'])->name('admin.course.settings.update');
        
        // Batch Management
        Route::get('/batches', [BatchController::class, 'index'])->name('admin.batches');
        Route::post('/batches', [BatchController::class, 'create'])->name('admin.batches.create');
        Route::put('/batches/{batch}/close', [BatchController::class, 'close'])->name('admin.batches.close');
        
        // Applications
        Route::get('/applications', [ApplicationController::class, 'index'])->name('admin.applications');
        Route::put('/applications/{application}/approve', [ApplicationController::class, 'approve'])->name('admin.applications.approve');
        Route::put('/applications/{application}/reject', [ApplicationController::class, 'reject'])->name('admin.applications.reject');
        Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('admin.applications.show');
        Route::post('/applications/bulk-action', [ApplicationController::class, 'bulkAction'])->name('admin.applications.bulk-action');
        Route::get('/applications/export', [ApplicationController::class, 'export'])->name('admin.applications.export');
        Route::post('/applications/{application}/resend-notification', [ApplicationController::class, 'resendNotification'])->name('admin.applications.resend-notification');
        // Payment Methods
        Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('admin.payment.methods');
        Route::post('/payment-methods', [PaymentMethodController::class, 'store'])->name('admin.payment.methods.store');
        Route::put('/payment-methods/{method}', [PaymentMethodController::class, 'update'])->name('admin.payment.methods.update');
        Route::put('/payment-methods/{method}/toggle', [PaymentMethodController::class, 'toggle'])->name('admin.payment.methods.toggle');
        
        // Time Conditions
        Route::get('/time-conditions', [TimeConditionController::class, 'index'])->name('admin.time.conditions');
        Route::put('/time-conditions/{condition}', [TimeConditionController::class, 'update'])->name('admin.time.conditions.update');
    });
});


Route::get('/test-all-sms/{phone?}', function($phone = '01753477957') {
    $smsService = app(\App\Services\SmsService::class);
    
    // Test data
    $student = new \stdClass();
    $student->name = 'Test Student';
    $student->phone = $phone;
    
    $application = new \stdClass();
    $application->id = 123;
    $application->rejection_reason = 'Insufficient score';
    $application->batch = new \stdClass();
    $application->batch->name = 'January 2025';
    $application->batch->start_date = now()->addDays(7);
    
    $student->classSession = new \stdClass();
    $student->classSession->time = 'Morning 8:00 AM';
    $student->classSession->days = 'Sun, Tue, Thu';
    
    $courseSetting = new \stdClass();
    $courseSetting->contact_number = '01712345678';
    
    $messages = [
        'submitted' => \App\Helpers\SmsTemplates::applicationSubmitted($student, $application),
        'approved' => \App\Helpers\SmsTemplates::applicationApproved($student, $application, $courseSetting),
        'rejected' => \App\Helpers\SmsTemplates::applicationRejected($student, $application),
        'welcome' => \App\Helpers\SmsTemplates::welcomeMessage($student),
        'payment_reminder' => \App\Helpers\SmsTemplates::paymentReminder($student, 8000),
        'class_reminder' => \App\Helpers\SmsTemplates::classReminder($student, 'Morning 8:00 AM', 'Sun, Tue, Thu'),
        'score_update' => \App\Helpers\SmsTemplates::scoreUpdate($student, 'Mock Test 1', '7.5/9'),
    ];
    
    return response()->json([
        'phone' => $phone,
        'templates' => $messages,
        'info' => 'Use /send-test-sms-template/{type}/{phone} to send specific SMS'
    ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});

Route::get('/send-test-sms-template/{type}/{phone?}', function($type, $phone = '01753477957') {
    $smsService = app(\App\Services\SmsService::class);
    
    // Same test data as above...
    $student = new \stdClass();
    $student->name = 'Test Student';
    $student->phone = $phone;
    
    $application = new \stdClass();
    $application->id = 123;
    $application->rejection_reason = 'Insufficient score';
    $application->batch = new \stdClass();
    $application->batch->name = 'January 2025';
    $application->batch->start_date = now()->addDays(7);
    
    $student->classSession = new \stdClass();
    $student->classSession->time = 'Morning 8:00 AM';
    $student->classSession->days = 'Sun, Tue, Thu';
    
    $courseSetting = new \stdClass();
    $courseSetting->contact_number = '01712345678';
    
    $message = match($type) {
        'submitted' => \App\Helpers\SmsTemplates::applicationSubmitted($student, $application),
        'approved' => \App\Helpers\SmsTemplates::applicationApproved($student, $application, $courseSetting),
        'rejected' => \App\Helpers\SmsTemplates::applicationRejected($student, $application),
        'welcome' => \App\Helpers\SmsTemplates::welcomeMessage($student),
        'payment' => \App\Helpers\SmsTemplates::paymentReminder($student, 8000),
        'class' => \App\Helpers\SmsTemplates::classReminder($student, 'Morning 8:00 AM', 'Sun, Tue, Thu'),
        'score' => \App\Helpers\SmsTemplates::scoreUpdate($student, 'Mock Test 1', '7.5/9'),
        default => 'Invalid type'
    };
    
    if ($message === 'Invalid type') {
        return response()->json(['error' => 'Invalid SMS type']);
    }
    
    $result = $smsService->send($phone, $message);
    
    return response()->json([
        'success' => $result,
        'type' => $type,
        'phone' => $phone,
        'message' => $message,
        'status' => $result ? 'SMS sent successfully!' : 'SMS failed!'
    ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});