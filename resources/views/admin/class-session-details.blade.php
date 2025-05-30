@extends('layouts.admin')

@section('title', 'Class Session Details')
@section('header')
    <div class="flex items-center justify-between">
        <h1>{{ $session->session_name }} - Details</h1>
        <a href="{{ route('admin.class.sessions') }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Sessions
        </a>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Session Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Session Details -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Session Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Session Name</label>
                    <p class="font-medium text-gray-900">{{ $session->session_name }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Time</label>
                    <p class="font-medium text-gray-900">{{ $session->time }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Days</label>
                    <p class="font-medium text-gray-900">{{ $session->days }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Current Students</label>
                    <p class="font-medium text-gray-900">{{ $session->current_count }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Batch</label>
                    <p class="font-medium text-gray-900">{{ $session->batch->name }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Batch Status</label>
                    <p class="font-medium">
                        @if($session->batch->is_active)
                            <span class="text-green-600">Active</span>
                        @else
                            <span class="text-gray-600">Inactive</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Students List -->
        <div class="bg-white shadow-md rounded-lg">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold">Enrolled Students ({{ $session->students->count() }})</h3>
            </div>
            
            @if($session->students->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($session->students as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                        <div class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $student->profession)) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $student->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $student->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ strtoupper($student->course_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $student->score }}/40
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($student->application)
                                        @if($student->application->status == 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($student->application->status == 'approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Rejected
                                            </span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $student->created_at->format('d M Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p>No students enrolled in this session yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Session Actions -->
    <div class="space-y-6">
        <!-- Quick Stats -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Session Stats</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Students:</span>
                    <span class="font-semibold text-gray-900">{{ $session->current_count }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Approved:</span>
                    <span class="font-semibold text-green-600">
                        {{ $session->students->filter(function($student) { 
                            return $student->application && $student->application->status == 'approved'; 
                        })->count() }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Pending:</span>
                    <span class="font-semibold text-yellow-600">
                        {{ $session->students->filter(function($student) { 
                            return $student->application && $student->application->status == 'pending'; 
                        })->count() }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Created:</span>
                    <span class="font-semibold text-gray-900">{{ $session->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.class.sessions') }}" 
                   class="w-full bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                    </svg>
                    Back to Sessions
                </a>
                
                @if($session->current_count == 0)
                <form action="{{ route('admin.class.sessions.destroy', $session) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this session? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Session
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection