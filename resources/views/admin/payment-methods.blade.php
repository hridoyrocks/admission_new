@extends('layouts.admin')

@section('title', 'Payment Methods')
@section('header', 'Payment Methods')

@section('content')
<!-- Add New Payment Method -->
<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h2 class="text-xl font-semibold mb-4">Add New Payment Method</h2>
    <form action="{{ route('admin.payment.methods.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" name="name" placeholder="Method Name" required
                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <input type="text" name="account_number" placeholder="Account Number" required
                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <input type="text" name="instructions" placeholder="Instructions" required
                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>
        <button type="submit" class="mt-4 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
            Add Method
        </button>
    </form>
</div>

<!-- Existing Payment Methods -->
<div class="space-y-4">
    @foreach($methods as $method)
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <h3 class="text-lg font-semibold mb-2">
                    {{ $method->name }}
                    @if($method->is_active)
                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                    @endif
                </h3>
                
                <form action="{{ route('admin.payment.methods.update', $method) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                        <input type="text" name="account_number" value="{{ $method->account_number }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Instructions</label>
                        <textarea name="instructions" rows="2" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ $method->instructions }}</textarea>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 text-white py-1 px-3 rounded text-sm hover:bg-blue-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
            
            <form action="{{ route('admin.payment.methods.toggle', $method) }}" method="POST" class="ml-4">
                @csrf
                @method('PUT')
                <button type="submit" class="text-sm {{ $method->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}">
                    {{ $method->is_active ? 'Disable' : 'Enable' }}
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection
