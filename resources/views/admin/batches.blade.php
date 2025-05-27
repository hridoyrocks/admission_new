@extends('layouts.admin')

@section('title', 'Batch Management')
@section('header', 'Batch Management')

@section('content')
<!-- Create New Batch -->
<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h2 class="text-xl font-semibold mb-4">Create New Batch</h2>
    <form action="{{ route('admin.batches.create') }}" method="POST" class="flex gap-4">
        @csrf
        <input type="text" name="name" placeholder="Batch Name (e.g., February 2025)" required
            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        <input type="date" name="start_date" required
            class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
            Create Batch
        </button>
    </form>
</div>

<!-- Existing Batches -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sessions</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($batches as $batch)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">
                        {{ $batch->name }}
                        @if($batch->is_active)
                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $batch->start_date->format('Y-m-d') }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($batch->status == 'open')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Open</span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Closed</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    @foreach($batch->classSessions as $session)
                        <div>{{ $session->session_name }}: {{ $session->current_count }} students</div>
                    @endforeach
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    @if($batch->is_active && $batch->status == 'open')
                        <form action="{{ route('admin.batches.close', $batch) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="text-red-600 hover:text-red-900">Close Batch</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection