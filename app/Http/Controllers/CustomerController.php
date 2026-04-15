<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    public function index()
    {
        Gate::authorize('customer view');
        
        $customers = Customer::orderBy('id', 'desc')->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        Gate::authorize('customer add');

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:customers,phone',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', __('messages.customers.created'));
    }

    public function update(Request $request, Customer $customer)
    {
        Gate::authorize('customer update');

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:customers,phone,' . $customer->id,
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', __('messages.customers.updated'));
    }

    public function destroy(Customer $customer)
    {
        Gate::authorize('customer delete');

        $customer->delete();
        return redirect()->route('customers.index')->with('success', __('messages.customers.deleted'));
    }
}
