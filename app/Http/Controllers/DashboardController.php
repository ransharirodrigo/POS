<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $todaySales = Sale::whereBetween('created_at', [$today, $todayEnd])
            ->where('status', 'completed')
            ->count();

        $todayRevenue = Sale::whereBetween('created_at', [$today, $todayEnd])
            ->where('status', 'completed')
            ->sum('total');

        $monthSales = Sale::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', 'completed')
            ->count();

        $monthRevenue = Sale::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', 'completed')
            ->sum('total');

        $totalProducts = Product::count();
        $totalCustomers = Customer::count();

        $recentSales = Sale::with(['customer', 'staff'])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $topProducts = SaleItem::select('product_name', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('sale', function ($query) {
                $query->where('status', 'completed');
            })
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'todaySales',
            'todayRevenue',
            'monthSales',
            'monthRevenue',
            'totalProducts',
            'totalCustomers',
            'recentSales',
            'topProducts'
        ));
    }
}