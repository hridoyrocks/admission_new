@extends('layouts.admin')

@section('title', 'Batch Management')
@section('header', 'Batch Management')

@section('content')
<!-- Create New Batch -->
<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h2 class="text-xl font-semibold mb-4">Create New Batch</h2>
    <form action="{{ route('admin.batches.create') }}" method="POST" id="createBatchForm">
        @csrf
        <div class="space-y-4">
            <!-- Batch Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Batch Name</label>
                    <input type="text" name="name" placeholder="Batch Name den ekhane" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <!-- Class Sessions -->
            <div class="border-t pt-4">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold text-gray-700">Class Sessions</h3>
                    <button type="button" onclick="addSession()" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                        + Add Session
                    </button>
                </div>
                
                <div id="sessionsContainer" class="space-y-3">
                    <!-- Sessions will be added here -->
                </div>
                
                
            </div>
            
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 w-full md:w-auto">
                Create Batch
            </button>
        </div>
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

<script>
let sessionCount = 0;

// Add initial session on page load
document.addEventListener('DOMContentLoaded', function() {
    addSession();
});

function addSession() {
    sessionCount++;
    const container = document.getElementById('sessionsContainer');
    const sessionDiv = document.createElement('div');
    sessionDiv.className = 'border p-4 rounded-lg bg-gray-50 relative';
    sessionDiv.id = `session-${sessionCount}`;
    
    sessionDiv.innerHTML = `
        <div class="absolute top-2 right-2">
            ${sessionCount > 1 ? `<button type="button" onclick="removeSession(${sessionCount})" class="text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>` : ''}
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Session Name</label>
                <input type="text" name="sessions[${sessionCount}][session_name]" 
                    placeholder="e.g., Morning Session" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                <input type="text" name="sessions[${sessionCount}][time]" 
                    placeholder="e.g., 8:00 AM" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Days</label>
                <input type="text" name="sessions[${sessionCount}][days]" 
                    placeholder="e.g., Sunday, Tuesday, Thursday" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    `;
    
    container.appendChild(sessionDiv);
}

function removeSession(id) {
    const session = document.getElementById(`session-${id}`);
    if (session) {
        session.remove();
    }
}
</script>
@endsection