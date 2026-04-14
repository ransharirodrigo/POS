<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('id', 'desc')->get();
        return view('staff.index', compact('employees'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'username' => 'required|string|max:75|unique:employees,username',
            'phone' => 'required|string|size:10',
            'role' => 'required|string',
            'password' => 'required|string|max:75',
        ]);

        Employee::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        return redirect()->route('staff.index')->with('success', __('messages.staff.created'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'username' => 'required|string|max:75|unique:employees,username,' . $employee->id,
            'phone' => 'required|string|size:10',
            'role' => 'nullable|string',
            'password' => 'nullable|string|max:75',
        ]);

        $data = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'phone' => $validated['phone'],
            'is_active' => $request->has('is_active'),
        ];

        if ($request->filled('role')) {
            $data['role'] = $validated['role'];
        }

        $employee->update($data);

        if ($request->filled('password')) {
            $employee->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('staff.index')->with('success', __('messages.staff.updated'));
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('staff.index')->with('success', __('messages.staff.deleted'));
    }
}