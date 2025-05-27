<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::all();
        return view('admin.payment-methods', compact('methods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'required|string',
            'instructions' => 'required|string'
        ]);

        PaymentMethod::create($validated);
        
        return redirect()->back()->with('success', 'Payment method added successfully!');
    }

    public function update(Request $request, PaymentMethod $method)
    {
        $validated = $request->validate([
            'account_number' => 'required|string',
            'instructions' => 'required|string'
        ]);

        $method->update($validated);
        
        return redirect()->back()->with('success', 'Payment method updated successfully!');
    }

    public function toggle(PaymentMethod $method)
    {
        $method->update(['is_active' => !$method->is_active]);
        
        return redirect()->back()->with('success', 'Payment method status updated!');
    }
}

