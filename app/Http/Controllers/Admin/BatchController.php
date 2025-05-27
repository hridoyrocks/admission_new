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
            'start_date' => 'required|date'
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

            // Create default sessions
            $defaultSessions = [
                ['session_name' => 'Morning Session', 'time' => '8:00 AM', 'days' => 'Sunday, Tuesday, Thursday'],
                ['session_name' => 'Afternoon Session', 'time' => '2:00 PM', 'days' => 'Sunday, Tuesday, Thursday'],
                ['session_name' => 'Evening Session', 'time' => '7:00 PM', 'days' => 'Sunday, Tuesday, Thursday'],
                ['session_name' => 'Weekend Session', 'time' => '10:00 AM', 'days' => 'Friday, Saturday']
            ];

            foreach ($defaultSessions as $sessionData) {
                $batch->classSessions()->create($sessionData);
            }

            DB::commit();
            
            return redirect()->back()->with('success', 'New batch created successfully!');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to create batch.');
        }
    }

    public function close(Batch $batch)
    {
        $batch->update(['status' => 'closed', 'is_active' => false]);
        return redirect()->back()->with('success', 'Batch closed successfully!');
    }
}

