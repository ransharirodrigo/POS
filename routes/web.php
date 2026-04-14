<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    if (Auth::check()) {
         Log::info('checked ');
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
