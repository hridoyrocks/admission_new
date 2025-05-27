{{-- resources/views/admin/application-details.blade.php --}}
@extends('layouts.admin')

@section('title', 'Application Details')
@section('header')
    <div class="flex items-center justify-between">
        <h1>Application Details #{{ $application->id }}</h1>
        <a href="{{ route('admin.applications') }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Applications
        </a>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Student Information -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Student Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Full Name</label>
                    <p class="font-medium text-gray-900">{{ $application->student->name }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Email Address</label>
                    <p class="font-medium text-gray-900">{{ $application->student->email }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Phone Number</label>
                    <p class="font-medium text-gray-900">{{ $application->student->phone }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Profession</label>
                    <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $application->student->profession)) }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Course Type</label>
                    <p class="font-medium text-gray-900">{{ strtoupper($application->student->course_type) }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Test Score</label>
                    <p class="font-medium text-gray-900">{{ $application->student->score }}/40</p>
                </div>
            </div>
        </div>

        <!-- Class Information -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Class Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Batch Name</label>
                    <p class="font-medium text-gray-900">{{ $application->batch->name }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Class Time</label>
                    <p class="font-medium text-gray-900">{{ $application->student->classSession->time ?? 'Not Assigned' }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Class Days</label>
                    <p class="font-medium text-gray-900">{{ $application->student->classSession->days ?? 'Not Assigned' }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Session Name</label>
                    <p class="font-medium text-gray-900">{{ $application->student->classSession->session_name ?? 'Not Assigned' }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Batch Start Date</label>
                    <p class="font-medium text-gray-900">{{ $application->batch->start_date->format('d M Y') }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Batch Status</label>
                    <p class="font-medium">
                        @if($application->batch->status == 'open')
                            <span class="text-green-600">Open</span>
                        @else
                            <span class="text-red-600">Closed</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Payment Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Payment Method</label>
                    <p class="font-medium text-gray-900">{{ $application->payment_method }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Transaction ID</label>
                    <p class="font-medium text-gray-900">{{ $application->payment_id }}</p>
                </div>
                @if($courseSetting)
                <div>
                    <label class="text-sm text-gray-600">Course Fee</label>
                    <p class="font-medium text-gray-900">৳{{ number_format($courseSetting->fee) }}</p>
                </div>
                @endif
                <div>
                    <label class="text-sm text-gray-300">Payment Status</label>
                    <p class="font-medium">
                        @if($application->status == 'approved')
                            <span class="text-green-600">Verified</span>
                        @elseif($application->status == 'pending')
                            <span class="text-yellow-600">Pending Verification</span>
                        @else
                            <span class="text-red-600">Not Verified</span>
                        @endif
                    </p>
                </div>
            </div>
            
            @if($application->screenshot)
            <div class="mt-4">
                <label class="text-sm text-gray-600 block mb-2">Payment Screenshot</label>
                <div class="border rounded-lg overflow-hidden bg-gray-50 p-4">
                    <img src="{{ asset('storage/' . $application->screenshot) }}" 
                        alt="Payment Screenshot" 
                        class="max-w-full h-auto rounded cursor-pointer"
                        onclick="window.open('{{ asset('storage/' . $application->screenshot) }}', '_blank')">
                    <p class="text-xs text-gray-500 mt-2">Click image to view full size</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Application Status -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Application Status</h3>
            <div class="text-center">
                @if($application->status == 'pending')
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-yellow-100 rounded-full mb-4">
                        <svg class="w-10 h-10 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Pending Review
                    </span>
                @elseif($application->status == 'approved')
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                        Approved
                    </span>
                @else
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                        <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                        Rejected
                    </span>
                @endif
            </div>
            
            <div class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Applied:</span>
                    <span class="font-medium">{{ $application->created_at->format('d M Y, h:i A') }}</span>
                </div>
                
                @if($application->approved_at)
                <div class="flex justify-between">
                    <span class="text-gray-600">Approved:</span>
                    <span class="font-medium">{{ $application->approved_at->format('d M Y, h:i A') }}</span>
                </div>
                @endif
                
                @if($application->rejected_at)
                <div class="flex justify-between">
                    <span class="text-gray-600">Rejected:</span>
                    <span class="font-medium">{{ $application->rejected_at->format('d M Y, h:i A') }}</span>
                </div>
                @endif
            </div>

            @if($application->rejection_reason)
            <div class="mt-4 p-3 bg-red-50 rounded-lg">
                <p class="text-sm font-medium text-red-800 mb-1">Rejection Reason:</p>
                <p class="text-sm text-red-700">{{ $application->rejection_reason }}</p>
            </div>
            @endif
        </div>

        <!-- Actions -->
        @if($application->status == 'pending')
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Quick Actions</h3>
            <div class="space-y-3">
                <form action="{{ route('admin.applications.approve', $application) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Approve Application
                    </button>
                </form>
                
                <button onclick="showRejectForm()" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Reject Application
                </button>
                
                <div id="rejectForm" class="hidden mt-4">
                    <form action="{{ route('admin.applications.reject', $application) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <textarea name="rejection_reason" rows="3" placeholder="Reason for rejection (optional)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 mb-2"></textarea>
                        <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700">
                            Confirm Rejection
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Actions</h3>
            <form action="{{ route('admin.applications.resend-notification', $application) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Resend Notification
                </button>
            </form>
        </div>
        @endif

        <!-- Contact Options -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Contact Student</h3>
            <div class="space-y-3">
                <a href="mailto:{{ $application->student->email }}" class="block text-blue-600 hover:text-blue-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Send Email
                </a>
                <a href="tel:{{ $application->student->phone }}" class="block text-blue-600 hover:text-blue-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Call {{ $application->student->phone }}
                </a>
            </div>
        </div>

        <!-- Related Applications -->
        @if(isset($similarApplications) && $similarApplications->count() > 0)
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Similar Applications</h3>
            <div class="space-y-3">
                @foreach($similarApplications as $similar)
                <a href="{{ route('admin.applications.show', $similar) }}" class="block p-3 bg-gray-50 rounded hover:bg-gray-100 transition">
                    <div class="text-sm">
                        <p class="font-medium text-gray-900">{{ $similar->student->name }}</p>
                        <p class="text-gray-600">{{ ucfirst($similar->student->profession) }} • Score: {{ $similar->student->score }}/40</p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($similar->status == 'pending')
                                <span class="text-yellow-600">Pending</span>
                            @elseif($similar->status == 'approved')
                                <span class="text-green-600">Approved</span>
                            @else
                                <span class="text-red-600">Rejected</span>
                            @endif
                        </p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function showRejectForm() {
    document.getElementById('rejectForm').classList.toggle('hidden');
}
</script>
@endsection