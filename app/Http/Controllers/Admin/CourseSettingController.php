<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseSetting;
use Illuminate\Http\Request;

class CourseSettingController extends Controller
{
    public function index()
    {
        $setting = CourseSetting::first();
        return view('admin.course-settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'duration' => 'required|string',
            'classes' => 'required|string',
            'fee' => 'required|numeric|min:0',
            'materials' => 'required|string',
            'mock_tests' => 'required|string',
            'additional_info' => 'nullable|string',
            'youtube_link' => 'nullable|url',
            'contact_number' => 'required|string'
        ]);

        // Convert additional_info from textarea to array
        if (isset($validated['additional_info'])) {
            $validated['additional_info'] = array_map('trim', explode("\n", $validated['additional_info']));
        }

        $setting = CourseSetting::firstOrCreate(
            ['id' => 1],
            $validated
        );

        $setting->update($validated);

        return redirect()->back()->with('success', 'Course settings updated successfully!');
    }
}
