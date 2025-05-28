@extends('layouts.admin')

@section('title', 'Course Settings')
@section('header', 'Course Settings')

@push('styles')
<style>
    .setting-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .setting-card:hover {
        border-color: #e5e7eb;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    .section-header {
        position: relative;
        padding-left: 20px;
    }
    .section-header::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 24px;
        background: #3b82f6;
        border-radius: 2px;
    }
    .info-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        background: #eff6ff;
        color: #3b82f6;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .preview-box {
        background: #f9fafb;
        border: 1px dashed #d1d5db;
        border-radius: 8px;
        padding: 20px;
    }
    .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }
    .input-with-icon {
        padding-left: 40px;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-xl p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold mb-2">Course Configuration</h2>
               
            </div>
            <div class="bg-white bg-opacity-20 rounded-xl p-6">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.course.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="bg-white shadow-lg rounded-xl p-8 mb-6">
            <h3 class="section-header text-xl font-bold text-gray-800 mb-6">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="setting-card bg-gray-50 rounded-lg p-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Course Duration
                    </label>
                    <input type="text" name="duration" value="{{ $setting->duration ?? '' }}" required
                        placeholder="e.g., 2 মাস"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                   
                </div>
                
                <div class="setting-card bg-gray-50 rounded-lg p-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Class Schedule
                    </label>
                    <input type="text" name="classes" value="{{ $setting->classes ?? '' }}" required
                        placeholder="e.g., সপ্তাহে 3 দিন"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                   
                </div>
                
                <div class="setting-card bg-gray-50 rounded-lg p-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Course Fee
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">৳</span>
                        <input type="number" name="fee" value="{{ $setting->fee ?? '' }}" required min="0"
                            placeholder="8000"
                            class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                   
                </div>
            </div>
        </div>

        <!-- Course Features -->
        <div class="bg-white shadow-lg rounded-xl p-8 mb-6">
            <h3 class="section-header text-xl font-bold text-gray-800 mb-6">Course Features</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="setting-card bg-gray-50 rounded-lg p-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Platform
                    </label>
                    <input type="text" name="materials" value="{{ $setting->materials ?? '' }}" required
                        placeholder="e.g., Free PDF + Videos"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    
                </div>
                
                <div class="setting-card bg-gray-50 rounded-lg p-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Mock Tests
                    </label>
                    <input type="text" name="mock_tests" value="{{ $setting->mock_tests ?? '' }}" required
                        placeholder="e.g., 5টি"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white shadow-lg rounded-xl p-8 mb-6">
            <h3 class="section-header text-xl font-bold text-gray-800 mb-6">Contact Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="setting-card bg-gray-50 rounded-lg p-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Contact Number
                    </label>
                    <input type="text" name="contact_number" value="{{ $setting->contact_number ?? '' }}" required
                        placeholder="e.g., 01712345678"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    
                </div>
                
                <div class="setting-card bg-gray-50 rounded-lg p-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        YouTube Channel
                    </label>
                    <input type="url" name="youtube_link" value="{{ $setting->youtube_link ?? '' }}"
                        placeholder="https://youtube.com/@channel"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    
                </div>
            </div>
        </div>

        <!-- Additional Features -->
        <div class="bg-white shadow-lg rounded-xl p-8 mb-6">
            <h3 class="section-header text-xl font-bold text-gray-800 mb-6">Additional Features</h3>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center justify-between">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Course Benefits
                    </span>
                    <span class="info-badge">One item per line</span>
                </label>
                <textarea name="additional_info" rows="6" 
                    placeholder="ekta line ekti alada kichu thakle den"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none">{{ is_array($setting->additional_info ?? null) ? implode("\n", $setting->additional_info) : ($setting->additional_info ?? '') }}</textarea>
                
            </div>
        </div>

        <!-- Current Settings Preview -->
        @if($setting)
        <div class="preview-box mb-6">
            <h4 class="font-semibold text-gray-700 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Current Settings Preview
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Duration:</span>
                    <span class="font-medium ml-2">{{ $setting->duration ?? 'Not set' }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Fee:</span>
                    <span class="font-medium ml-2">৳{{ number_format($setting->fee ?? 0) }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Classes:</span>
                    <span class="font-medium ml-2">{{ $setting->classes ?? 'Not set' }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Mock Tests:</span>
                    <span class="font-medium ml-2">{{ $setting->mock_tests ?? 'Not set' }}</span>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <button type="button" onclick="window.location.reload()" 
                class="bg-gray-200 text-gray-700 py-3 px-8 rounded-lg hover:bg-gray-300 transition font-semibold">
                Reset
            </button>
            <button type="submit" 
                class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-10 rounded-lg hover:from-blue-700 hover:to-blue-800 transition font-semibold flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Save All Changes
            </button>
        </div>
    </form>
</div>
@endsection