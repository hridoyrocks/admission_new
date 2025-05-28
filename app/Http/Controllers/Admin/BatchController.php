<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::with('classSessions')->orderBy('created_at', 'desc')->get();
        return view('admin.batches', compact('batches'));
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'sessions' => 'required|array|min:1',
            'sessions.*.session_name' => 'required|string|max:255',
            'sessions.*.time' => 'required|string|max:255',
            'sessions.*.days' => 'required|string|max:255'
        ]);

        DB::beginTransaction();
        
        try {
            // Deactivate all other batches
            Batch::where('is_active', true)->update(['is_active' => false]);
            
            // Create new batch
            $batch = Batch::create([
                'name' => $validated['name'],
                'start_date' => $validated['start_date'],
                'is_active' => true,
                'status' => 'open'
            ]);

            // Create custom sessions from form input
            foreach ($validated['sessions'] as $sessionData) {
                $batch->classSessions()->create([
                    'session_name' => $sessionData['session_name'],
                    'time' => $sessionData['time'],
                    'days' => $sessionData['days']
                ]);
            }

            DB::commit();
            
            return redirect()->back()->with('success', 'New batch created successfully with ' . count($validated['sessions']) . ' sessions!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to create batch: ' . $e->getMessage());
        }
    }

    public function close(Batch $batch)
    {
        $batch->update(['status' => 'closed', 'is_active' => false]);
        return redirect()->back()->with('success', 'Batch closed successfully!');
    }
}