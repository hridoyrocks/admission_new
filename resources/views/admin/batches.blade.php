@extends('layouts.admin')

@section('title', 'Batch Management')
@section('header', 'Batch Management')

@push('styles')
<style>
    .batch-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .batch-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .batch-active {
        border-left-color: #10b981;
        background: linear-gradient(135deg, #f0fff4 0%, #f7fafc 100%);
    }
    .batch-closed {
        border-left-color: #ef4444;
        background: linear-gradient(135deg, #fef2f2 0%, #f7fafc 100%);
    }
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-active {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    .status-closed {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Create New Batch -->
    <div class="bg-white shadow-lg rounded-xl p-6">
        <div class="flex items-center mb-6">
            <div class="p-3 bg-blue-100 rounded-lg mr-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800">Create New Batch</h2>
        </div>
        
        <form action="{{ route('admin.batches.create') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Batch Name</label>
                    <input type="text" name="name" placeholder="e.g., January 2025" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
               
            </div>
            
            <div class="mt-6 flex items-center gap-4">
                <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition font-medium">
                    Create Batch
                </button>
                <div class="text-sm text-gray-600 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <span class="font-medium">Note:</span> After creating the batch, you can add class sessions from the 
                    <a href="{{ route('admin.class.sessions') }}" class="text-blue-600 hover:text-blue-700 underline">Class Sessions</a> page.
                </div>
            </div>
        </form>
    </div>

    <!-- Existing Batches -->
    <div class="bg-white shadow-lg rounded-xl p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6">All Batches</h3>
        
        @if($batches->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($batches as $batch)
                <div class="batch-card bg-white border border-gray-200 rounded-lg p-6 {{ $batch->is_active ? 'batch-active' : ($batch->status == 'closed' ? 'batch-closed' : '') }}">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ $batch->name }}</h4>
                            @if($batch->description)
                                <p class="text-sm text-gray-600 mb-2">{{ $batch->description }}</p>
                            @endif
                            <div class="flex items-center gap-2">
                                <span class="status-badge {{ $batch->is_active ? 'status-active' : 'status-closed' }}">
                                    {{ $batch->is_active ? 'Active' : 'Closed' }}
                                </span>
                                @if($batch->is_active)
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Current</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span><strong>Start Date:</strong> {{ $batch->start_date->format('d M Y') }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span><strong>Sessions:</strong> {{ $batch->class_sessions_count }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span><strong>Applications:</strong> {{ $batch->applications_count }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span><strong>Created:</strong> {{ $batch->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    <!-- Batch Sessions Preview -->
                    @if($batch->classSessions->count() > 0)
                        <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs font-medium text-gray-700 mb-2">Sessions:</p>
                            <div class="space-y-1">
                                @foreach($batch->classSessions->take(2) as $session)
                                    <div class="text-xs text-gray-600 flex items-center justify-between">
                                        <span>{{ $session->session_name }}</span>
                                        <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded">{{ $session->current_count }} students</span>
                                    </div>
                                @endforeach
                                @if($batch->classSessions->count() > 2)
                                    <div class="text-xs text-gray-500 italic">+{{ $batch->classSessions->count() - 2 }} more sessions</div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-xs text-yellow-700">
                                <span class="font-medium">No sessions yet.</span> 
                                <a href="{{ route('admin.class.sessions') }}" class="underline hover:text-yellow-800">Add sessions</a>
                            </p>
                        </div>
                    @endif
                    
                    <!-- Actions -->
                    <div class="flex gap-2 pt-4 border-t">
                        @if($batch->is_active && $batch->status == 'open')
                            <form action="{{ route('admin.batches.close', $batch) }}" method="POST" class="flex-1" 
                                onsubmit="return confirm('Are you sure you want to close this batch?');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="w-full bg-red-100 text-red-700 py-2 px-3 rounded-lg hover:bg-red-200 transition text-sm font-medium">
                                    Close Batch
                                </button>
                            </form>
                        @elseif($batch->status == 'closed')
                            <form action="{{ route('admin.batches.reactivate', $batch) }}" method="POST" class="flex-1"
                                onsubmit="return confirm('Are you sure you want to reactivate this batch? This will deactivate the current active batch.');">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="w-full bg-green-100 text-green-700 py-2 px-3 rounded-lg hover:bg-green-200 transition text-sm font-medium">
                                    Reactivate
                                </button>
                            </form>
                        @endif
                        
                        @if($batch->applications_count == 0 && $batch->class_sessions_count == 0)
                            <form action="{{ route('admin.batches.destroy', $batch) }}" method="POST" 
                                onsubmit="return confirm('Are you sure you want to delete this batch? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-gray-100 text-gray-700 py-2 px-3 rounded-lg hover:bg-gray-200 transition text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No Batches Found</h3>
                <p class="text-gray-500">Start by creating your first batch above.</p>
            </div>
        @endif
    </div>
</div>
@endsection