<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $employee = Employee::where('username', $credentials['username'])->first();

        if (!$employee || !password_verify($credentials['password'], $employee->password)) {
            return back()->withErrors([
                'username' => __('messages.auth.failed'),
            ])->onlyInput('username');
        }

        if (!$employee->is_active) {
            return back()->withErrors([
                'username' => __('messages.auth.inactive'),
            ])->onlyInput('username');
        }

        Auth::login($employee);
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}