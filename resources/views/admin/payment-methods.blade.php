@extends('layouts.admin')

@section('title', 'Payment Methods')
@section('header', 'Payment Methods')

@push('styles')
<style>
    .payment-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .payment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .payment-card.active {
        border-left-color: #10b981;
    }
    .payment-card.inactive {
        border-left-color: #ef4444;
    }
    .toggle-switch {
        position: relative;
        width: 48px;
        height: 24px;
        background-color: #e5e7eb;
        border-radius: 24px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .toggle-switch.active {
        background-color: #10b981;
    }
    .toggle-switch::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        background-color: white;
        border-radius: 50%;
        top: 2px;
        left: 2px;
        transition: transform 0.3s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .toggle-switch.active::after {
        transform: translateX(24px);
    }
    .payment-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 24px;
    }
    .bkash-icon { background: #e2136e20; color: #e2136e; }
    .nagad-icon { background: #f6921e20; color: #f6921e; }
    .bank-icon { background: #3b82f620; color: #3b82f6; }
    .rocket-icon { background: #8b47a620; color: #8b47a6; }
</style>
@endpush

@section('content')
<!-- Add New Payment Method -->
<div class="bg-white shadow-lg rounded-xl p-8 mb-8">
    <div class="flex items-center mb-6">
        <div class="p-3 bg-blue-100 rounded-lg mr-4">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Add New Payment Method</h2>
    </div>
    
    <form action="{{ route('admin.payment.methods.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-tag mr-2 text-gray-500"></i>Method Name
                </label>
                <select name="name" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select Payment Method</option>
                    <option value="bKash">bKash</option>
                    <option value="Nagad">Nagad</option>
                    <option value="Rocket">Rocket</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Cash">Cash</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-hashtag mr-2 text-gray-500"></i>Account Number
                </label>
                <input type="text" name="account_number" placeholder="e.g., 01712345678" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div class="md:col-span-2 lg:col-span-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-info-circle mr-2 text-gray-500"></i>Instructions (Bangla)
                </label>
                <input type="text" name="instructions" placeholder="e.g., Send Money করুন এবং Reference এ আপনার নাম লিখুন" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-8 rounded-lg hover:from-blue-700 hover:to-blue-800 transition font-semibold flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Payment Method
            </button>
        </div>
    </form>
</div>

<!-- Existing Payment Methods -->
<div class="mb-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <div class="p-2 bg-purple-100 rounded-lg mr-3">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
        </div>
        Active Payment Methods
    </h3>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    @foreach($methods as $method)
    <div class="payment-card bg-white shadow-lg rounded-xl overflow-hidden {{ $method->is_active ? 'active' : 'inactive' }}">
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center">
                    <div class="payment-icon {{ strtolower(str_replace(' ', '-', $method->name)) }}-icon mr-4">
                        @if(str_contains(strtolower($method->name), 'bkash'))
                            <i class="fas fa-mobile-alt"></i>
                        @elseif(str_contains(strtolower($method->name), 'nagad'))
                            <i class="fas fa-mobile"></i>
                        @elseif(str_contains(strtolower($method->name), 'rocket'))
                            <i class="fas fa-rocket"></i>
                        @elseif(str_contains(strtolower($method->name), 'bank'))
                            <i class="fas fa-university"></i>
                        @else
                            <i class="fas fa-credit-card"></i>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $method->name }}</h3>
                        <div class="flex items-center mt-1">
                            @if($method->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Toggle Switch -->
                <form action="{{ route('admin.payment.methods.toggle', $method) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="toggle-switch {{ $method->is_active ? 'active' : '' }}"></button>
                </form>
            </div>
            
            <form action="{{ route('admin.payment.methods.update', $method) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-hashtag mr-1 text-gray-400"></i>Account Number
                        </label>
                        <input type="text" name="account_number" value="{{ $method->account_number }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-info-circle mr-1 text-gray-400"></i>Instructions
                        </label>
                        <textarea name="instructions" rows="2" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none">{{ $method->instructions }}</textarea>
                    </div>
                </div>
                
                <div class="flex justify-between items-center pt-4 border-t">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        Updated: {{ $method->updated_at->diffForHumans() }}
                    </div>
                    <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition font-medium">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
</div>

@if($methods->isEmpty())
<div class="bg-gray-50 rounded-xl p-12 text-center">
    <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-700 mb-2">No Payment Methods Added</h3>
    <p class="text-gray-500">Start by adding your first payment method above.</p>
</div>
@endif

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
@endsection