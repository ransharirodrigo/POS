<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('super.admin')->group(function () {
        Route::prefix('staff')->group(function () {
            Route::get('/', [StaffController::class, 'index'])->name('staff.index');
            Route::get('/create', [StaffController::class, 'create'])->name('staff.create');
            Route::post('/', [StaffController::class, 'store'])->name('staff.store');
            Route::put('/{employee}', [StaffController::class, 'update'])->name('staff.update');
            Route::delete('/{employee}', [StaffController::class, 'destroy'])->name('staff.destroy');
        });
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});