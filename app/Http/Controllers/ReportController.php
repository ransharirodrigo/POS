<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    public function index()
    {
        Gate::authorize('view reports');
        return view('reports.index');
    }

    public function salesSummary(Request $request)
    {
        Gate::authorize('view reports');
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfMonth();

        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();

        $totalSales = $sales->count();
        $totalRevenue = $sales->sum('total');
        $totalDiscount = $sales->sum('discount');

        $salesByPayment = $sales->groupBy('payment_method')->map(function ($group) {
            return $group->sum('total');
        });

        $salesByStaff = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->with('staff')
            ->get()
            ->groupBy('staff_id')
            ->map(function ($group) {
                return [
                    'name' => $group->first()->staff->fullName,
                    'total' => $group->sum('total'),
                    'count' => $group->count()
                ];
            });

        return view('reports.sales', compact('sales', 'totalSales', 'totalRevenue', 'totalDiscount', 'salesByPayment', 'salesByStaff', 'startDate', 'endDate'));
    }

    public function topProducts(Request $request)
    {
        Gate::authorize('view reports');
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : now()->endOfMonth();
        $limit = $request->limit ?? 10;

        $topProducts = SaleItem::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_sales'))
            ->whereHas('sale', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'completed');
            })
            ->groupBy('product_name')
            ->orderByDesc('total_sales')
            ->limit($limit)
            ->get();

        return view('reports.top-products', compact('topProducts', 'startDate', 'endDate', 'limit'));
    }

    public function inventory(Request $request)
    {
        Gate::authorize('view reports');

        $categoryId = $request->category_id;

        $products = Product::with(['category', 'variants'])
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->get()
            ->map(function ($product) {
                $totalStock = $product->variants->sum('quantity');
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'category' => $product->category->name ?? '-',
                    'total_stock' => $totalStock,
                    'is_low_stock' => $totalStock < 10,
                    'variants' => $product->variants->map(function ($variant) {
                        return [
                            'size' => $variant->size,
                            'color' => $variant->color,
                            'quantity' => $variant->quantity,
                            'is_low' => $variant->quantity < 10
                        ];
                    })
                ];
            });

        $lowStockProducts = $products->filter(function ($p) {
            return $p['total_stock'] < 10 && $p['total_stock'] > 0;
        });

        $outOfStockProducts = $products->filter(function ($p) {
            return $p['total_stock'] == 0;
        });

        $categories = Category::where('is_active', true)->get();

        return view('reports.inventory', compact('products', 'lowStockProducts', 'outOfStockProducts', 'categories', 'categoryId'));
    }
}
