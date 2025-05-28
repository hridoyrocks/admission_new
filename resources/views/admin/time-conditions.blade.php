@extends('layouts.admin')

@section('title', 'Time Conditions')
@section('header', 'Class Time Conditions')

@push('styles')
<style>
    .profession-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .profession-card:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 28px;
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
        border-radius: 28px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #3b82f6;
    }
    input:checked + .slider:before {
        transform: translateX(32px);
    }
    .score-rule-item {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }
    .score-rule-item:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
    }
</style>
@endpush

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @foreach($conditions as $condition)
    <div class="profession-card bg-white shadow-lg rounded-xl overflow-hidden">
        <!-- Header -->
        <div class="p-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold capitalize">
                    @if($condition->profession == 'student')
                        <i class="fas fa-graduation-cap mr-2"></i>
                    @elseif($condition->profession == 'job_holder')
                        <i class="fas fa-briefcase mr-2"></i>
                    @else
                        <i class="fas fa-home mr-2"></i>
                    @endif
                    {{ str_replace('_', ' ', $condition->profession) }}
                </h3>
                <div class="bg-white bg-opacity-20 rounded-full px-3 py-1 text-sm">
                    {{ $condition->is_fixed ? 'Fixed Time' : 'Score Based' }}
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.time.conditions.update', $condition) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <!-- Toggle Switch -->
            <div class="mb-6 flex items-center justify-between bg-gray-50 rounded-lg p-4">
                <label class="font-medium text-gray-700">Time Assignment Method</label>
                <label class="switch">
                    <input type="checkbox" name="is_fixed" value="1" 
                        {{ $condition->is_fixed ? 'checked' : '' }}
                        onchange="toggleFixedTime{{ $condition->id }}(this.checked)">
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
                        placeholder="e.g., Morning 8:00 AM"
                        class="w-full px-4 py-3 border-2 border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg">
                    <p class="text-xs text-blue-700 mt-2">সব student এই সময়ে ক্লাস করবে</p>
                </div>
            </div>
            
            <!-- Score-based Rules -->
            <div id="scoreRules{{ $condition->id }}" class="{{ $condition->is_fixed ? 'hidden' : '' }} transition-all duration-300">
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-sm font-semibold text-gray-900">
                            <i class="fas fa-chart-line mr-2"></i>Score-based Time Rules
                        </label>
                        <button type="button" onclick="addScoreRule{{ $condition->id }}()" 
                            class="text-sm bg-green-500 text-white px-3 py-1 rounded-full hover:bg-green-600 transition">
                            <i class="fas fa-plus mr-1"></i> Add Rule
                        </button>
                    </div>
                    
                    <div class="space-y-3" id="rulesContainer{{ $condition->id }}">
                        @if($condition->score_rules)
                            @foreach($condition->score_rules as $index => $rule)
                            <div class="score-rule-item rounded-lg p-3" id="rule{{ $condition->id }}_{{ $index }}">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 grid grid-cols-7 gap-2 items-center">
                                        <input type="number" 
                                            name="score_rules[{{ $index }}][min_score]" 
                                            value="{{ $rule['min_score'] }}"
                                            placeholder="Min" 
                                            min="0" max="40" 
                                            class="col-span-1 px-2 py-2 border rounded-md text-center font-semibold">
                                        
                                        <span class="text-center text-gray-500">to</span>
                                        
                                        <input type="number" 
                                            name="score_rules[{{ $index }}][max_score]" 
                                            value="{{ $rule['max_score'] }}"
                                            placeholder="Max" 
                                            min="0" max="40" 
                                            class="col-span-1 px-2 py-2 border rounded-md text-center font-semibold">
                                        
                                        <span class="text-center text-gray-500">=</span>
                                        
                                        <input type="text" 
                                            name="score_rules[{{ $index }}][time]" 
                                            value="{{ $rule['time'] }}"
                                            placeholder="Time (e.g., 8:00 AM)" 
                                            class="col-span-3 px-3 py-2 border rounded-md">
                                    </div>
                                    <button type="button" 
                                        onclick="removeScoreRule{{ $condition->id }}({{ $index }})"
                                        class="text-red-500 hover:text-red-700 transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <div class="mt-3 p-3 bg-yellow-50 rounded-lg">
                        <p class="text-xs text-yellow-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Score range 0-40 এর মধ্যে থাকতে হবে। যেমন: 0-20 = Morning, 21-30 = Afternoon, 31-40 = Evening
                        </p>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="w-full mt-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-4 rounded-lg hover:from-blue-700 hover:to-blue-800 transition font-semibold">
                <i class="fas fa-save mr-2"></i>Update Condition
            </button>
        </form>
    </div>
    
    <script>
        let ruleCount{{ $condition->id }} = {{ $condition->score_rules ? count($condition->score_rules) : 0 }};
        
        function toggleFixedTime{{ $condition->id }}(isFixed) {
            const fixedDiv = document.getElementById('fixedTime{{ $condition->id }}');
            const rulesDiv = document.getElementById('scoreRules{{ $condition->id }}');
            
            if(isFixed) {
                fixedDiv.classList.remove('hidden');
                rulesDiv.classList.add('hidden');
            } else {
                fixedDiv.classList.add('hidden');
                rulesDiv.classList.remove('hidden');
            }
        }
        
        function addScoreRule{{ $condition->id }}() {
            const container = document.getElementById('rulesContainer{{ $condition->id }}');
            const newRule = document.createElement('div');
            newRule.className = 'score-rule-item rounded-lg p-3';
            newRule.id = `rule{{ $condition->id }}_${ruleCount{{ $condition->id }}}`;
            
            newRule.innerHTML = `
                <div class="flex items-center gap-2">
                    <div class="flex-1 grid grid-cols-7 gap-2 items-center">
                        <input type="number" 
                            name="score_rules[${ruleCount{{ $condition->id }}}][min_score]" 
                            placeholder="Min" 
                            min="0" max="40" 
                            class="col-span-1 px-2 py-2 border rounded-md text-center font-semibold">
                        
                        <span class="text-center text-gray-500">to</span>
                        
                        <input type="number" 
                            name="score_rules[${ruleCount{{ $condition->id }}}][max_score]" 
                            placeholder="Max" 
                            min="0" max="40" 
                            class="col-span-1 px-2 py-2 border rounded-md text-center font-semibold">
                        
                        <span class="text-center text-gray-500">=</span>
                        
                        <input type="text" 
                            name="score_rules[${ruleCount{{ $condition->id }}}][time]" 
                            placeholder="Time (e.g., 8:00 AM)" 
                            class="col-span-3 px-3 py-2 border rounded-md">
                    </div>
                    <button type="button" 
                        onclick="removeScoreRule{{ $condition->id }}(${ruleCount{{ $condition->id }}})"
                        class="text-red-500 hover:text-red-700 transition">
                        <i class="fas fa-trash"></i>
                    </button>
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

<!-- Font Awesome (for icons) -->
<script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
@endsection