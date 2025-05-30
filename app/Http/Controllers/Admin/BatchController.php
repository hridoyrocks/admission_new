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
        $batches = Batch::with(['classSessions', 'applications'])
            ->withCount(['classSessions', 'applications'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.batches', compact('batches'));
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:batches,name',
            'start_date' => 'required|date|after:today',
            'description' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        
        try {
            // Deactivate all other batches
            Batch::where('is_active', true)->update(['is_active' => false]);
            
            // Create new batch
            $batch = Batch::create([
                'name' => $validated['name'],
                'start_date' => $validated['start_date'],
                'description' => $validated['description'] ?? null,
                'is_active' => true,
                'status' => 'open'
            ]);

            DB::commit();
            
            return redirect()->back()->with('success', 'New batch "' . $batch->name . '" created successfully! You can now add class sessions for this batch.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to create batch: ' . $e->getMessage());
        }
    }

    public function close(Batch $batch)
    {
        $batch->update([
            'status' => 'closed', 
            'is_active' => false,
            'closed_at' => now()
        ]);
        
        return redirect()->back()->with('success', 'Batch "' . $batch->name . '" closed successfully!');
    }

    public function reactivate(Batch $batch)
    {
        // Deactivate all other batches first
        Batch::where('is_active', true)->update(['is_active' => false]);
        
        $batch->update([
            'status' => 'open',
            'is_active' => true,
            'closed_at' => null
        ]);
        
        return redirect()->back()->with('success', 'Batch "' . $batch->name . '" reactivated successfully!');
    }

    public function destroy(Batch $batch)
    {
        // Check if batch has applications
        if ($batch->applications()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete batch with existing applications.');
        }

        // Check if batch has sessions
        if ($batch->classSessions()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete batch with existing class sessions. Delete sessions first.');
        }

        $batchName = $batch->name;
        $batch->delete();
        
        return redirect()->back()->with('success', 'Batch "' . $batchName . '" deleted successfully!');
    }
}