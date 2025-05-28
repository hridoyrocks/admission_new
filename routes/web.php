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
        Route::get('/applications/export', [ApplicationController::class, 'export'])->name('admin.applications.export');
        Route::put('/applications/{application}/approve', [ApplicationController::class, 'approve'])->name('admin.applications.approve');
        Route::put('/applications/{application}/reject', [ApplicationController::class, 'reject'])->name('admin.applications.reject');
        Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('admin.applications.show');
        Route::post('/applications/bulk-action', [ApplicationController::class, 'bulkAction'])->name('admin.applications.bulk-action');
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


