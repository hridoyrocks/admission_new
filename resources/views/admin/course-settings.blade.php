@extends('layouts.admin')

@section('title', 'Course Settings')
@section('header', 'Course Settings')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <form action="{{ route('admin.course.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                <input type="text" name="duration" value="{{ $setting->duration ?? '' }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Classes</label>
                <input type="text" name="classes" value="{{ $setting->classes ?? '' }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Course Fee (টাকা)</label>
                <input type="number" name="fee" value="{{ $setting->fee ?? '' }}" required min="0"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Materials</label>
                <input type="text" name="materials" value="{{ $setting->materials ?? '' }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mock Tests</label>
                <input type="text" name="mock_tests" value="{{ $setting->mock_tests ?? '' }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                <input type="text" name="contact_number" value="{{ $setting->contact_number ?? '' }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">YouTube Channel Link</label>
                <input type="url" name="youtube_link" value="{{ $setting->youtube_link ?? '' }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Info (One per line)</label>
                <textarea name="additional_info" rows="4" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ is_array($setting->additional_info ?? null) ? implode("\n", $setting->additional_info) : ($setting->additional_info ?? '') }}</textarea>
            </div>
        </div>
        
        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection