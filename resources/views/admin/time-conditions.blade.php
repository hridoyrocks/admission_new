@extends('layouts.admin')

@section('title', 'Time Conditions')
@section('header', 'Class Time Conditions Management')

@push('styles')
<style>
    .profession-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
    }
    .profession-card:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .sync-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .available-times {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .time-chip {
        display: inline-block;
        background: white;
        border: 1px solid #d1d5db;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        margin: 0.25rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .time-chip:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    .time-chip.selected {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }
    .session-preview {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: 0.5rem;
        padding: 0.75rem;
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }
</style>

<!-- CSS for the switch -->
<style>
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 34px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #2196F3;
}

input:checked + .slider:before {
    transform: translateX(26px);
}
</style>
@endpush

@section('content')
<!-- Sync Information Banner -->
<div class="sync-banner">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold mb-1">🔄 Time Synchronization</h3>
            <p class="text-sm opacity-90">Time conditions are automatically synchronized with class sessions</p>
        </div>
        <form action="{{ route('admin.time.conditions.sync') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-lg transition flex items-center">
                <i class="fas fa-sync mr-2"></i>
                Sync All Times
            </button>
        </form>
    </div>
</div>

<!-- Available Times from Sessions -->
<div class="available-times">
    <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
        <i class="fas fa-clock mr-2 text-blue-600"></i>
        Available Time Slots (from Class Sessions)
    </h4>
    @if($activeBatch)
        <div class="flex flex-wrap gap-2">
            @forelse($availableTimes as $timeOption)
                <span class="time-chip" onclick="selectTime('{{ $timeOption['time'] }}')">
                    {{ $timeOption['display'] }}
                </span>
            @empty
                <p class="text-gray-500 text-sm">No class sessions found in active batch</p>
            @endforelse
        </div>
        <p class="text-xs text-gray-600 mt-2">
            <i class="fas fa-info-circle mr-1"></i>
            Click on any time slot to use it in your time conditions
        </p>
    @else
        <p class="text-yellow-600 text-sm">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            No active batch found. Please create and activate a batch first.
        </p>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @foreach($conditions as $condition)
    <div class="profession-card bg-white shadow-lg rounded-xl overflow-hidden">
        <!-- Header -->
        <div class="p-4 bg-gradient-to-r from-gray-700 to-gray-800 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        @if($condition->profession == 'student')
                            <i class="fas fa-graduation-cap text-white"></i>
                        @elseif($condition->profession == 'job_holder')
                            <i class="fas fa-briefcase text-white"></i>
                        @else
                            <i class="fas fa-home text-white"></i>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">{{ ucfirst(str_replace('_', ' ', $condition->profession)) }}</h3>
                        <p class="text-sm opacity-80">
                            {{ $condition->is_fixed ? 'Fixed Time' : 'Score Based' }}
                        </p>
                    </div>
                </div>
                <div class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded">
                    @if($condition->profession == 'student')
                        📚 Students
                    @elseif($condition->profession == 'job_holder')
                        💼 Workers
                    @else
                        🏠 Housewives
                    @endif
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.time.conditions.update', $condition) }}" method="POST" class="p-6" id="form{{ $condition->id }}">
            @csrf
            @method('PUT')
            
           <!-- Toggle Switch Section - Updated -->
<div class="mb-6 flex items-center justify-between bg-gray-50 rounded-lg p-4">
    <div>
        <label class="font-medium text-gray-700 block">Assignment Method</label>
        <p class="text-xs text-gray-500">Fixed time or score-based</p>
    </div>
    <label class="switch">
        <input type="checkbox" name="is_fixed" value="1" 
            {{ $condition->is_fixed ? 'checked' : '' }}
            onchange="toggleMethod{{ $condition->id }}(this.checked)"
            id="isFixedToggle{{ $condition->id }}">
        <span class="slider"></span>
    </label>
</div>
            
            <!-- Fixed Time Input -->
            <div id="fixedTime{{ $condition->id }}" class="{{ !$condition->is_fixed ? 'hidden' : '' }} transition-all duration-300">
                <div class="bg-blue-50 rounded-lg p-4 mb-4">
                    <label class="block text-sm font-semibold text-blue-900 mb-3">
                        <i class="fas fa-clock mr-2"></i>Fixed Class Time
                    </label>
                    <input type="text" name="fixed_time" value="{{ $condition->fixed_time }}" 
                        placeholder="Select from available times or enter custom"
                        class="w-full px-4 py-3 border-2 border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        id="fixedTimeInput{{ $condition->id }}">
                    
                    <!-- Available Times for Selection -->
                    <div class="mt-3">
                        <p class="text-xs text-blue-700 mb-2">Quick Select:</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($availableTimes as $timeOption)
                                <button type="button" 
                                    onclick="setFixedTime{{ $condition->id }}('{{ $timeOption['time'] }}')"
                                    class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-800 px-2 py-1 rounded transition">
                                    {{ $timeOption['time'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Score-based Rules -->
            <div id="scoreRules{{ $condition->id }}" class="{{ $condition->is_fixed ? 'hidden' : '' }} transition-all duration-300">
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-sm font-semibold text-gray-900">
                            <i class="fas fa-chart-line mr-2 text-purple-600"></i>Score-based Rules
                        </label>
                        <button type="button" onclick="addScoreRule{{ $condition->id }}()" 
                            class="text-sm bg-green-500 text-white px-3 py-1 rounded-full hover:bg-green-600 transition">
                            <i class="fas fa-plus mr-1"></i> Add Rule
                        </button>
                    </div>
                    
                    <div class="space-y-3" id="rulesContainer{{ $condition->id }}">
                        @if($condition->score_rules)
                            @foreach($condition->score_rules as $index => $rule)
                            <div class="score-rule-item rounded-lg p-3 border" id="rule{{ $condition->id }}_{{ $index }}">
                                <div class="grid grid-cols-7 gap-2 items-end">
                                    <div>
                                        <label class="text-xs text-gray-500">Min</label>
                                        <input type="number" 
                                            name="score_rules[{{ $index }}][min_score]" 
                                            value="{{ $rule['min_score'] }}"
                                            min="0" max="40" 
                                            class="w-full px-2 py-1 border rounded text-center text-sm">
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500">Max</label>
                                        <input type="number" 
                                            name="score_rules[{{ $index }}][max_score]" 
                                            value="{{ $rule['max_score'] }}"
                                            min="0" max="40" 
                                            class="w-full px-2 py-1 border rounded text-center text-sm">
                                    </div>
                                    <div class="col-span-4">
                                        <label class="text-xs text-gray-500">Time</label>
                                        <input type="text" 
                                            name="score_rules[{{ $index }}][time]" 
                                            value="{{ $rule['time'] }}"
                                            placeholder="Select time" 
                                            class="w-full px-2 py-1 border rounded text-sm"
                                            id="scoreTimeInput{{ $condition->id }}_{{ $index }}">
                                        
                                        <!-- Quick time selection -->
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach($availableTimes as $timeOption)
                                                <button type="button" 
                                                    onclick="setScoreTime{{ $condition->id }}({{ $index }}, '{{ $timeOption['time'] }}')"
                                                    class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-1 py-0.5 rounded">
                                                    {{ explode(' ', $timeOption['time'])[1] ?? $timeOption['time'] }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" 
                                            onclick="removeScoreRule{{ $condition->id }}({{ $index }})"
                                            class="text-red-500 hover:text-red-700 transition p-1">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <div class="mt-3 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <p class="text-xs text-yellow-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Ranges: 0-40, no overlaps. Example: 0-15→6PM, 16-25→7PM, 26-40→8PM
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Session Preview -->
            <div class="session-preview">
                <h5 class="font-medium text-gray-700 mb-2">📋 Current Assignment Preview:</h5>
                @if($condition->is_fixed)
                    <p class="text-sm">All {{ str_replace('_', ' ', $condition->profession) }}s → <strong>{{ $condition->fixed_time }}</strong></p>
                @else
                    @if($condition->score_rules)
                        @foreach($condition->score_rules as $rule)
                            <p class="text-xs">Score {{ $rule['min_score'] }}-{{ $rule['max_score'] }} → <strong>{{ $rule['time'] }}</strong></p>
                        @endforeach
                    @endif
                @endif
            </div>
            
            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-4 rounded-lg hover:from-blue-700 hover:to-blue-800 transition font-semibold mt-4">
                <i class="fas fa-save mr-2"></i>Update & Sync
            </button>
        </form>
    </div>
    
    <script>
    let ruleCount{{ $condition->id }} = {{ $condition->score_rules ? count($condition->score_rules) : 0 }};
    
    function toggleMethod{{ $condition->id }}(isFixed) {
        const fixedDiv = document.getElementById('fixedTime{{ $condition->id }}');
        const rulesDiv = document.getElementById('scoreRules{{ $condition->id }}');
        const hiddenInput = document.getElementById('isFixedHidden{{ $condition->id }}');
        const toggleInput = document.getElementById('isFixedToggle{{ $condition->id }}');
        
        if(isFixed) {
            fixedDiv.classList.remove('hidden');
            rulesDiv.classList.add('hidden');
            hiddenInput.value = '1';
            toggleInput.value = '1';
        } else {
            fixedDiv.classList.add('hidden');
            rulesDiv.classList.remove('hidden');
            hiddenInput.value = '0';
            toggleInput.value = '0';
        }
    }
    
    // Initialize the toggle state on page load
    document.addEventListener('DOMContentLoaded', function() {
        const isChecked = document.getElementById('isFixedToggle{{ $condition->id }}').checked;
        toggleMethod{{ $condition->id }}(isChecked);
    });
    
    function setFixedTime{{ $condition->id }}(time) {
        document.getElementById('fixedTimeInput{{ $condition->id }}').value = time;
    }
    
    function setScoreTime{{ $condition->id }}(index, time) {
        document.getElementById('scoreTimeInput{{ $condition->id }}_' + index).value = time;
    }
    
    function addScoreRule{{ $condition->id }}() {
        const container = document.getElementById('rulesContainer{{ $condition->id }}');
        const newRule = document.createElement('div');
        newRule.className = 'score-rule-item rounded-lg p-3 border';
        newRule.id = `rule{{ $condition->id }}_${ruleCount{{ $condition->id }}}`;
        
        newRule.innerHTML = `
            <div class="grid grid-cols-7 gap-2 items-end">
                <div>
                    <label class="text-xs text-gray-500">Min</label>
                    <input type="number" 
                        name="score_rules[${ruleCount{{ $condition->id }}}][min_score]" 
                        min="0" max="40" 
                        class="w-full px-2 py-1 border rounded text-center text-sm">
                </div>
                <div>
                    <label class="text-xs text-gray-500">Max</label>
                    <input type="number" 
                        name="score_rules[${ruleCount{{ $condition->id }}}][max_score]" 
                        min="0" max="40" 
                        class="w-full px-2 py-1 border rounded text-center text-sm">
                </div>
                <div class="col-span-4">
                    <label class="text-xs text-gray-500">Time</label>
                    <input type="text" 
                        name="score_rules[${ruleCount{{ $condition->id }}}][time]" 
                        placeholder="Select time" 
                        class="w-full px-2 py-1 border rounded text-sm"
                        id="scoreTimeInput{{ $condition->id }}_${ruleCount{{ $condition->id }}}">
                    <div class="flex flex-wrap gap-1 mt-1">
                        @foreach($availableTimes as $timeOption)
                            <button type="button" 
                                onclick="setScoreTime{{ $condition->id }}(${ruleCount{{ $condition->id }}}, '{{ $timeOption['time'] }}')"
                                class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-1 py-0.5 rounded">
                                {{ explode(' ', $timeOption['time'])[1] ?? $timeOption['time'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="text-center">
                    <button type="button" 
                        onclick="removeScoreRule{{ $condition->id }}(${ruleCount{{ $condition->id }}})"
                        class="text-red-500 hover:text-red-700 transition p-1">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </div>
            </div>
        `;
        
        container.appendChild(newRule);
        ruleCount{{ $condition->id }}++;
    }
    
    function removeScoreRule{{ $condition->id }}(index) {
        const rule = document.getElementById(`rule{{ $condition->id }}_${index}`);
        if(rule) {
            rule.remove();
        }
    }
</script>
    @endforeach
</div>

<script>
function selectTime(time) {
    // Highlight selected time chip
    document.querySelectorAll('.time-chip').forEach(chip => {
        chip.classList.remove('selected');
    });
    event.target.classList.add('selected');
    
    // Copy to clipboard
    navigator.clipboard.writeText(time).then(() => {
        // Show brief feedback
        const originalText = event.target.textContent;
        event.target.textContent = 'Copied!';
        setTimeout(() => {
            event.target.textContent = originalText;
        }, 1000);
    });
}
</script>

@endsection