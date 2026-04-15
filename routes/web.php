<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;

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

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::get('/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
        Route::post('/', [CustomerController::class, 'store'])->name('customers.store');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    });

    Route::prefix('pos')->group(function () {
        Route::get('/', [SaleController::class, 'pos'])->name('pos.index');
    });

    Route::prefix('sales')->group(function () {
        Route::get('/', [SaleController::class, 'index'])->name('sales.index');
        Route::post('/', [SaleController::class, 'store'])->name('sales.store');
    });

    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/sales', [ReportController::class, 'salesSummary'])->name('reports.sales');
        Route::get('/top-products', [ReportController::class, 'topProducts'])->name('reports.top-products');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});