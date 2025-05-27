<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Application;
use App\Models\CourseSetting;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $activeBatch = Batch::where('is_active', true)->with('classSessions')->first();
        $totalApplications = Application::count();
        $pendingApplications = Application::where('status', 'pending')->count();
        $courseSetting = CourseSetting::first();
        
        $revenue = Application::where('status', 'approved')
            ->join('course_settings', function($join) {
                $join->on('course_settings.id', '=', DB::raw('1'));
            })
            ->sum('course_settings.fee');

        return view('admin.dashboard', compact(
            'activeBatch', 
            'totalApplications', 
            'pendingApplications', 
            'revenue',
            'courseSetting'
        ));
    }
}
