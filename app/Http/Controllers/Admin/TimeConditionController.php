<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeCondition;
use Illuminate\Http\Request;

class TimeConditionController extends Controller
{
    public function index()
    {
        $conditions = TimeCondition::all();
        return view('admin.time-conditions', compact('conditions'));
    }

    public function update(Request $request, TimeCondition $condition)
    {
        if ($request->input('is_fixed')) {
            $validated = $request->validate([
                'fixed_time' => 'required|string'
            ]);
            
            $condition->update([
                'is_fixed' => true,
                'fixed_time' => $validated['fixed_time'],
                'score_rules' => null
            ]);
        } else {
            $validated = $request->validate([
                'score_rules' => 'required|array',
                'score_rules.*.min_score' => 'required|integer|min:0|max:40',
                'score_rules.*.max_score' => 'required|integer|min:0|max:40',
                'score_rules.*.time' => 'required|string'
            ]);
            
            $condition->update([
                'is_fixed' => false,
                'fixed_time' => null,
                'score_rules' => $validated['score_rules']
            ]);
        }
        
        return redirect()->back()->with('success', 'Time condition updated successfully!');
    }
}
