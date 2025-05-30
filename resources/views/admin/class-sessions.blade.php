@extends('layouts.admin')

@section('title', 'Class Sessions Management')
@section('header', 'Class Sessions Management')

@push('styles')
<style>
    .session-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .session-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-left-color: #3b82f6;
    }
    .session-active {
        border-left-color: #10b981;
        background: linear-gradient(135deg, #f0fff4 0%, #f7fafc 100%);
    }
    .student-count-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .time-badge {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<!-- Add New Session -->
<div class="bg-white shadow-lg rounded-xl p-8 mb-8">
    <div class="flex items-center mb-6">
        <div class="p-3 bg-blue-100 rounded-lg mr-4">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Add New Class Session</h2>
    </div>
    
    <form action="{{ route('admin.class.sessions.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-layer-group mr-2 text-gray-500"></i>Batch
                </label>
                <select name="batch_id" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Batch</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ $batch->is_active ? 'selected' : '' }}>
                            {{ $batch->name }} 
                            @if($batch->is_active) (Active) @endif
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-tag mr-2 text-gray-500"></i>Session Name
                </label>
                <input type="text" name="session_name" placeholder="e.g., Morning Session" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-clock mr-2 text-gray-500"></i>Time
                </label>
                <input type="text" name="time" placeholder="e.g., 8:00 AM" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt mr-2 text-gray-500"></i>Days
                </label>
                <input type="text" name="days" placeholder="e.g., Sunday, Tuesday, Thursday" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-8 rounded-lg hover:from-blue-700 hover:to-blue-800 transition font-semibold flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Session
            </button>
        </div>
    </form>
</div>

<!-- Sessions List -->
<div class="mb-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <div class="p-2 bg-purple-100 rounded-lg mr-3">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
        </div>
        All Class Sessions
    </h3>
</div>

@if($sessions->groupBy('batch.name')->count() > 0)
    @foreach($sessions->groupBy('batch.name') as $batchName => $batchSessions)
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-lg font-semibold text-gray-700 flex items-center">
                <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                {{ $batchName }}
            </h4>
            <span class="text-sm text-gray-500">{{ $batchSessions->count() }} sessions</span>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($batchSessions as $session)
            <div class="session-card bg-white shadow-lg rounded-xl overflow-hidden {{ $session->batch->is_active ? 'session-active' : '' }}">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $session->session_name }}</h3>
                            <div class="flex items-center gap-3 mb-3">
                                <span class="time-badge">{{ $session->time }}</span>
                                <span class="student-count-badge">{{ $session->current_count }} students</span>
                            </div>
                        </div>
                        
                        @if($session->batch->is_active)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
                                Active Batch
                            </span>
                        @endif
                    </div>
                    
                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Days: <strong>{{ $session->days }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Batch: <strong>{{ $session->batch->name }}</strong></span>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.class.sessions.update', $session) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 gap-3">
                            <input type="text" name="session_name" value="{{ $session->session_name }}" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            
                            <input type="text" name="time" value="{{ $session->time }}" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            
                            <textarea name="days" rows="2" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none">{{ $session->days }}</textarea>
                        </div>
                        
                        <div class="flex justify-between items-center pt-4 border-t">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.class.sessions.show', $session) }}" 
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium rounded bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </a>
                                
                                @if($session->current_count == 0)
                                <form action="{{ route('admin.class.sessions.destroy', $session) }}" method="POST" class="inline" 
                                    onsubmit="return confirm('Are you sure you want to delete this session?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1 text-xs font-medium rounded bg-red-100 text-red-700 hover:bg-red-200 transition">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                            
                            <button type="submit" class="bg-blue-600 text-white py-1 px-4 rounded-lg hover:bg-blue-700 transition font-medium text-sm">
                                <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
@else
    <div class="bg-gray-50 rounded-xl p-12 text-center">
        <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">No Class Sessions Found</h3>
        <p class="text-gray-500">Start by adding your first class session above.</p>
    </div>
@endif

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
@endsection