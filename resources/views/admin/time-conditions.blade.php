@extends('layouts.admin')

@section('title', 'Time Conditions')
@section('header', 'Class Time Conditions')

@section('content')
<div class="space-y-6">
    @foreach($conditions as $condition)
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4 capitalize">{{ str_replace('_', ' ', $condition->profession) }}</h3>
        
        <form action="{{ route('admin.time.conditions.update', $condition) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_fixed" value="1" {{ $condition->is_fixed ? 'checked' : '' }}
                        onchange="toggleFixedTime{{ $condition->id }}(this.checked)"
                        class="mr-2">
                    <span>Fixed Time</span>
                </label>
            </div>
            
            <!-- Fixed Time Input -->
            <div id="fixedTime{{ $condition->id }}" class="{{ !$condition->is_fixed ? 'hidden' : '' }}">
                <label class="block text-sm font-medium text-gray-700 mb-2">Fixed Class Time</label>
                <input type="text" name="fixed_time" value="{{ $condition->fixed_time }}" 
                    placeholder="e.g., Morning 8:00 AM"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <!-- Score-based Rules -->
            <div id="scoreRules{{ $condition->id }}" class="{{ $condition->is_fixed ? 'hidden' : '' }}">
                <label class="block text-sm font-medium text-gray-700 mb-2">Score-based Rules</label>
                <div class="space-y-3" id="rulesContainer{{ $condition->id }}">
                    @if($condition->score_rules)
                        @foreach($condition->score_rules as $index => $rule)
                        <div class="flex gap-2 items-center">
                            <input type="number" name="score_rules[{{ $index }}][min_score]" value="{{ $rule['min_score'] }}"
                                placeholder="Min" min="0" max="40" class="w-20 px-2 py-1 border rounded">
                            <span>-</span>
                            <input type="number" name="score_rules[{{ $index }}][max_score]" value="{{ $rule['max_score'] }}"
                                placeholder="Max" min="0" max="40" class="w-20 px-2 py-1 border rounded">
                            <span>:</span>
                            <input type="text" name="score_rules[{{ $index }}][time]" value="{{ $rule['time'] }}"
                                placeholder="Time" class="flex-1 px-2 py-1 border rounded">
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
            
            <button type="submit" class="mt-4 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                Update Condition
            </button>
        </form>
    </div>
    
    <script>
        function toggleFixedTime{{ $condition->id }}(isFixed) {
            document.getElementById('fixedTime{{ $condition->id }}').classList.toggle('hidden', !isFixed);
            document.getElementById('scoreRules{{ $condition->id }}').classList.toggle('hidden', isFixed);
        }
    </script>
    @endforeach
</div>
@endsection