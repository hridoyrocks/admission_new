<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSession;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassSessionController extends Controller
{
    public function index()
    {
        $sessions = ClassSession::with(['batch', 'students'])
            ->orderBy('batch_id', 'desc')
            ->orderBy('time')
            ->get();
            
        $batches = Batch::orderBy('created_at', 'desc')->get();
        
        return view('admin.class-sessions', compact('sessions', 'batches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'session_name' => 'required|string|max:255',
            'time' => 'required|string|max:255',
            'days' => 'required|string|max:255'
        ]);

        ClassSession::create($validated);

        return redirect()->back()->with('success', 'Class session created successfully!');
    }

    public function update(Request $request, ClassSession $session)
    {
        $validated = $request->validate([
            'session_name' => 'required|string|max:255',
            'time' => 'required|string|max:255',
            'days' => 'required|string|max:255'
        ]);

        $session->update($validated);

        return redirect()->back()->with('success', 'Class session updated successfully!');
    }

    public function destroy(ClassSession $session)
    {
        // Check if session has students
        if ($session->students()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete session with enrolled students.');
        }

        $session->delete();

        return redirect()->back()->with('success', 'Class session deleted successfully!');
    }

    public function show(ClassSession $session)
    {
        $session->load(['batch', 'students.application']);
        
        return view('admin.class-session-details', compact('session'));
    }
}