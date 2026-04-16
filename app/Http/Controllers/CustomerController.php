<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CanDelete;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    use CanDelete;

    public function index()
    {
        Gate::authorize('customer view');

        $customers = Customer::orderBy('id', 'desc')->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $phone = $request->input('phone');
        
        if (empty($name) || empty($phone)) {
            return response()->json(['success' => false, 'errors' => ['name' => ['Name is required'], 'phone' => ['Phone is required']]], 422);
        }
        
        $exists = Customer::where('phone', $phone)->exists();
        if ($exists) {
            return response()->json(['success' => false, 'errors' => ['phone' => ['This phone number already exists']]], 422);
        }
        
        $customer = Customer::create([
            'name' => $name,
            'phone' => $phone,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true, 
            'customer' => ['id' => $customer->id, 'name' => $customer->name, 'phone' => $customer->phone]
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        Gate::authorize('customer update');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:customers,phone,' . $customer->id,
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $customer->update([
            'name' => $validator->validated()['name'],
            'phone' => $validator->validated()['phone'],
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => __('messages.customers.updated')]);
        }

        return redirect()->route('customers.index')->with('success', __('messages.customers.updated'));
    }

    public function destroy(Customer $customer)
    {
        Gate::authorize('customer delete');

        return $this->deleteIfNoRelated($customer, 'sales', 'customers.index', 'customers', __('messages.customers.deleted'));
    }

    public function search(Request $request)
    {
        $phone = $request->query('phone');
        $customer = Customer::where('phone', 'like', "%{$phone}%")->first();
        
        if ($customer) {
            return response()->json([
                'found' => true,
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                ]
            ]);
        }
        
        return response()->json(['found' => false]);
    }
}
