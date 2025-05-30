@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    .welcome-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .card-gradient-1 {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .card-gradient-2 {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    .card-gradient-3 {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    .card-gradient-4 {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    .activity-item {
        transition: background-color 0.3s ease;
    }
    .activity-item:hover {
        background-color: #f9fafb;
    }
    .session-card {
        transition: all 0.3s ease;
    }
    .session-card:hover {
        transform: translateX(5px);
        border-color: #3b82f6;
    }
</style>
@endpush

@section('content')


<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Applications Card -->
    <div class="stat-card bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 rounded-full card-gradient-1">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <span class="text-3xl font-bold text-gray-700">{{ $totalApplications }}</span>
        </div>
        <h3 class="text-gray-600 text-sm font-medium">মোট আবেদন</h3>
    </div>

    <!-- Pending Applications Card -->
    <div class="stat-card bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 rounded-full card-gradient-2">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="text-3xl font-bold text-gray-700">{{ $pendingApplications }}</span>
        </div>
        <h3 class="text-gray-600 text-sm font-medium">অপেক্ষমাণ আবেদন</h3>
        @if($pendingApplications > 0)
            <p class="text-xs text-yellow-600 mt-2 font-semibold">এখনই চেক করুন!</p>
        @endif
    </div>

    <!-- Revenue Card -->
    <div class="stat-card bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 rounded-full card-gradient-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="text-2xl font-bold text-gray-700">৳{{ number_format($revenue) }}</span>
        </div>
        <h3 class="text-gray-600 text-sm font-medium">মোট আয়</h3>
    </div>

    <!-- Active Batch Card -->
    <div class="stat-card bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 rounded-full card-gradient-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div class="text-right">
                <p class="text-lg font-bold text-gray-700">{{ $activeBatch->name ?? 'নাই' }}</p>
                @if($activeBatch)
                    <p class="text-xs text-gray-500">{{ $activeBatch->start_date->format('d M') }} থেকে</p>
                @endif
            </div>
        </div>
        <h3 class="text-gray-600 text-sm font-medium">চলমান ব্যাচ</h3>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column - 2/3 width -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">দ্রুত কার্যক্রম</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.applications') }}" class="group">
                    <div class="p-4 bg-blue-50 rounded-lg text-center hover:bg-blue-100 transition">
                        <svg class="w-8 h-8 text-blue-600 mx-auto mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">আবেদন দেখুন</span>
                    </div>
                </a>
                <a href="{{ route('admin.batches') }}" class="group">
                    <div class="p-4 bg-green-50 rounded-lg text-center hover:bg-green-100 transition">
                        <svg class="w-8 h-8 text-green-600 mx-auto mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">ব্যাচ ব্যবস্থাপনা</span>
                    </div>
                </a>
                <a href="{{ route('admin.course.settings') }}" class="group">
                    <div class="p-4 bg-purple-50 rounded-lg text-center hover:bg-purple-100 transition">
                        <svg class="w-8 h-8 text-purple-600 mx-auto mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">কোর্স সেটিংস</span>
                    </div>
                </a>
                <a href="{{ route('admin.payment.methods') }}" class="group">
                    <div class="p-4 bg-yellow-50 rounded-lg text-center hover:bg-yellow-100 transition">
                        <svg class="w-8 h-8 text-yellow-600 mx-auto mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <span class="text-sm font-medium text-gray-700">পেমেন্ট পদ্ধতি</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Current Batch Sessions -->
        @if($activeBatch)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">চলমান ব্যাচ সেশনসমূহ</h3>
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                    {{ $activeBatch->name }}
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($activeBatch->classSessions as $session)
                <div class="session-card border-2 border-gray-200 rounded-lg p-5 hover:shadow-lg">
                    <div class="flex items-start justify-between mb-3">
                        <h4 class="font-semibold text-gray-900 text-lg">{{ $session->session_name }}</h4>
                        <div class="text-center bg-blue-50 px-3 py-1 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $session->current_count }}</div>
                            <div class="text-xs text-gray-600">শিক্ষার্থী</div>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>সময়: <strong>{{ $session->time }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>দিন: <strong>{{ $session->days }}</strong></span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-lg p-8 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">কোন সক্রিয় ব্যাচ নেই</h3>
            <p class="text-gray-600 mb-4">নতুন ব্যাচ তৈরি করতে নিচের বাটনে ক্লিক করুন</p>
            <a href="{{ route('admin.batches') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                নতুন ব্যাচ তৈরি করুন
            </a>
        </div>
        @endif
    </div>

    <!-- Right Column - 1/3 width -->
    <div class="space-y-8">
        <!-- Course Info -->
        @if($courseSetting)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">কোর্সের তথ্য</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">মেয়াদ</span>
                    <span class="text-sm font-medium text-gray-900">{{ $courseSetting->duration }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">ক্লাস</span>
                    <span class="text-sm font-medium text-gray-900">{{ $courseSetting->classes }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">কোর্স ফি</span>
                    <span class="text-sm font-medium text-gray-900">৳{{ number_format($courseSetting->fee) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">মক টেস্ট</span>
                    <span class="text-sm font-medium text-gray-900">{{ $courseSetting->mock_tests }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm text-gray-600">যোগাযোগ</span>
                    <span class="text-sm font-medium text-gray-900">{{ $courseSetting->contact_number }}</span>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t">
                <a href="{{ route('admin.course.settings') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1">
                    <span>সেটিংস পরিবর্তন করুন</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
        @endif

        <!-- Recent Applications -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">সাম্প্রতিক আবেদন</h3>
                <a href="{{ route('admin.applications') }}" class="text-blue-600 hover:text-blue-700 text-sm">
                    সব দেখুন
                </a>
            </div>
            @php
                $recentApplications = \App\Models\Application::with('student')
                    ->latest()
                    ->limit(5)
                    ->get();
            @endphp
            @if($recentApplications->count() > 0)
                <div class="space-y-3">
                    @foreach($recentApplications as $app)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $app->student->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $app->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full font-medium
                            @if($app->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($app->status == 'approved') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @if($app->status == 'pending') অপেক্ষমাণ
                            @elseif($app->status == 'approved') অনুমোদিত
                            @else প্রত্যাখ্যাত
                            @endif
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-4">কোন আবেদন পাওয়া যায়নি</p>
            @endif
        </div>

        <!-- System Info -->
        
    </div>
</div>
@endsection