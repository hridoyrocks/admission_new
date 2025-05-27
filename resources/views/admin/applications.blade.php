@extends('layouts.admin')

@section('title', 'Applications Management')
@section('header', 'Applications Management')

@push('styles')
<style>
    .filter-section { transition: all 0.3s ease; }
    .stats-card { transition: transform 0.2s ease; }
    .stats-card:hover { transform: translateY(-2px); }
    .table-row-hover:hover { background-color: #f9fafb; }
    .checkbox-custom { cursor: pointer; }
    /* Icon styling */
    .icon-sm { width: 1.25rem; height: 1.25rem; }
    .icon-xs { width: 1rem; height: 1rem; }
</style>
@endpush

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="stats-card bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 uppercase">Total Applications</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-600 mt-2">
            <span class="text-green-600">+{{ $stats['today'] ?? 0 }}</span> today
        </p>
    </div>
    
    <div class="stats-card bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 uppercase">Pending</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['pending'] ?? 0 }}</p>
            </div>
            <div class="p-3 bg-yellow-100 rounded-full">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-600 mt-2">Requires action</p>
    </div>
    
    <div class="stats-card bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 uppercase">Approved</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['approved'] ?? 0 }}</p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-600 mt-2">This month: {{ $stats['this_month'] ?? 0 }}</p>
    </div>
    
    <div class="stats-card bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 uppercase">Rejected</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['rejected'] ?? 0 }}</p>
            </div>
            <div class="p-3 bg-red-100 rounded-full">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-600 mt-2">This week: {{ $stats['this_week'] ?? 0 }}</p>
    </div>
</div>

<!-- Filters Section - Always visible search bar -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-4">
        <form method="GET" action="{{ route('admin.applications') }}">
            <!-- Main Search Bar -->
            <div class="flex gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by name, email, phone, or ID..." 
                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                    Search
                </button>
                <a href="{{ route('admin.applications.export', request()->all()) }}" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Applications Table -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <form id="bulkActionForm" method="POST" action="{{ route('admin.applications.bulk-action') }}">
        @csrf
        <!-- Bulk Actions Bar -->
        <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <select name="action" id="bulkAction" class="px-3 py-1 border border-gray-300 rounded-md text-sm">
                        <option value="">Bulk Actions</option>
                        <option value="approve">Approve Selected</option>
                        <option value="reject">Reject Selected</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button type="submit" onclick="return confirmBulkAction()" class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 text-sm disabled:bg-gray-400 flex items-center gap-1" disabled id="bulkActionBtn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Apply
                    </button>
                </div>
                <span class="text-sm text-gray-600">
                    <span id="selectedCount">0</span> selected
                </span>
            </div>
            
            <div class="text-sm text-gray-600">
                Showing <strong>{{ $applications->firstItem() ?? 0 }}</strong> to <strong>{{ $applications->lastItem() ?? 0 }}</strong> of <strong>{{ $applications->total() }}</strong> entries
            </div>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">
                            <input type="checkbox" id="selectAll" class="rounded checkbox-custom">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="?{{ http_build_query(array_merge(request()->all(), ['sort_by' => 'id', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-gray-700">
                                ID
                                @if(request('sort_by') == 'id')
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="{{ request('sort_order') == 'asc' ? 'M5 10l5-5 5 5' : 'M15 10l-5 5-5-5' }}"/>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="?{{ http_build_query(array_merge(request()->all(), ['sort_by' => 'created_at', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-gray-700">
                                Applied
                                @if(request('sort_by') == 'created_at')
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="{{ request('sort_order') == 'asc' ? 'M5 10l5-5 5 5' : 'M15 10l-5 5-5-5' }}"/>
                                    </svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($applications as $application)
                    <tr class="table-row-hover">
                        <td class="px-4 py-4">
                            <input type="checkbox" name="applications[]" value="{{ $application->id }}" class="applicationCheckbox rounded checkbox-custom">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            #{{ $application->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $application->student->name }}</div>
                                <div class="text-sm text-gray-500">{{ $application->student->email }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $application->student->phone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst(str_replace('_', ' ', $application->student->profession)) }}
                                </span>
                                <div class="text-gray-500 mt-1">
                                    Score: {{ $application->student->score }}/40 | {{ strtoupper($application->student->course_type) }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <div class="font-medium">{{ $application->student->classSession->time ?? 'N/A' }}</div>
                                <div class="text-gray-500">{{ $application->student->classSession->days ?? 'N/A' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">{{ $application->payment_method }}</div>
                                <div class="text-gray-500">{{ $application->payment_id }}</div>
                                @if($application->screenshot)
                                    <a href="{{ asset('storage/' . $application->screenshot) }}" target="_blank" 
                                        class="inline-block mt-1 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded hover:bg-blue-200 transition">
                                        View Screenshot
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($application->status == 'pending')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <svg class="icon-xs mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Pending
                                </span>
                            @elseif($application->status == 'approved')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <svg class="icon-xs mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Approved
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    <svg class="icon-xs mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Rejected
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>{{ $application->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $application->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.applications.show', $application) }}" 
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-700 hover:bg-gray-200">
                                    View
                                </a>
                                
                                @if($application->status == 'pending')
                                    <button onclick="approveApplication({{ $application->id }})" 
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-700 hover:bg-green-200">
                                        Approve
                                    </button>
                                    
                                    <button onclick="showRejectModal({{ $application->id }})" 
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-red-100 text-red-700 hover:bg-red-200">
                                        Reject
                                    </button>
                                @else
                                    <button onclick="resendNotification({{ $application->id }})" 
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-700 hover:bg-blue-200">
                                        Resend
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2">No applications found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
    
    <!-- Pagination -->
    <div class="px-6 py-4 bg-gray-50 border-t">
        {{ $applications->withQueryString()->links() }}
    </div>
</div>

<!-- Rejection Modal -->
<div id="rejectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Reject Application</h3>
            <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="rejectionForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason (Optional)</label>
                <textarea name="rejection_reason" rows="4" 
                    placeholder="Enter the reason for rejection..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"></textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">
                    Reject Application
                </button>
                <button type="button" onclick="closeRejectModal()" 
                    class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Select All functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.applicationCheckbox');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    updateSelectedCount();
});

// Update selected count
document.querySelectorAll('.applicationCheckbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
});

function updateSelectedCount() {
    const selectedCount = document.querySelectorAll('.applicationCheckbox:checked').length;
    document.getElementById('selectedCount').textContent = selectedCount;
    document.getElementById('bulkActionBtn').disabled = selectedCount === 0;
}

// Confirm bulk action
function confirmBulkAction() {
    const action = document.getElementById('bulkAction').value;
    const selectedCount = document.querySelectorAll('.applicationCheckbox:checked').length;
    
    if (!action) {
        alert('Please select an action');
        return false;
    }
    
    if (selectedCount === 0) {
        alert('Please select at least one application');
        return false;
    }
    
    return confirm(`Are you sure you want to ${action} ${selectedCount} application(s)?`);
}

// Approve Application
function approveApplication(id) {
    if (confirm('Are you sure you want to approve this application?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/applications/${id}/approve`;
        form.innerHTML = `
            @csrf
            @method('PUT')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// Show Reject Modal
function showRejectModal(id) {
    document.getElementById('rejectionModal').classList.remove('hidden');
    document.getElementById('rejectionForm').action = `/admin/applications/${id}/reject`;
}

// Close Reject Modal
function closeRejectModal() {
    document.getElementById('rejectionModal').classList.add('hidden');
    document.getElementById('rejectionForm').reset();
}

// Resend Notification
function resendNotification(id) {
    if (confirm('Resend notification to this student?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/applications/${id}/resend-notification`;
        form.innerHTML = `@csrf`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush