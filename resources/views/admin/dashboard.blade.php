@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stats Cards -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm font-medium">Active Batch</h3>
        <p class="text-2xl font-bold text-gray-800">{{ $activeBatch->name ?? 'None' }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm font-medium">Total Applications</h3>
        <p class="text-2xl font-bold text-gray-800">{{ $totalApplications }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm font-medium">Pending Approval</h3>
        <p class="text-2xl font-bold text-yellow-600">{{ $pendingApplications }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm font-medium">Revenue</h3>
        <p class="text-2xl font-bold text-green-600">৳{{ number_format($revenue) }}</p>
    </div>
</div>

@if($activeBatch)
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-4">Current Batch Sessions</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($activeBatch->classSessions as $session)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $session->session_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $session->time }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $session->days }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $session->current_count }} students</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection