<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class SaleController extends Controller
{
    public function index()
    {
        Gate::authorize('view sales');
        
        $sales = Sale::with(['staff', 'customer', 'items'])->orderBy('id', 'desc')->paginate(15);
        return view('sales.index', compact('sales'));
    }

    public function pos()
    {
        Gate::authorize('manage sales');

        $products = Product::with(['category', 'variants'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        $staff = Employee::where('is_active', true)
            ->orderBy('first_name')
            ->get();

        $customers = Customer::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pos.index', compact('products', 'categories', 'staff', 'customers'));
    }

    public function store(Request $request)
    {
        Gate::authorize('manage sales');

        try {
            $validated = $request->validate([
                'staff_id' => 'required|exists:employees,id',
                'customer_id' => 'nullable|exists:customers,id',
                'subtotal' => 'required|numeric|min:0',
                'discount' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'paid_amount' => 'required|numeric|min:0',
                'change_amount' => 'required|numeric|min:0',
                'payment_method' => 'required|in:cash,card,online',
                'items' => 'required|array|min:1',
            ]);

            foreach ($validated['items'] as $item) {
                $availableQty = 0;
                $variant = ProductVariant::find($item['variantId']);
                if (!$variant) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Invalid product variant selected'
                    ], 422);
                }
                $availableQty = $variant->quantity;

                if ($item['quantity'] > $availableQty) {
                    $itemName = $item['variantName'] ? $item['productName'] . ' (' . $item['variantName'] . ')' : $item['productName'];
                    return response()->json([
                        'success' => false,
                        'error' => 'Insufficient stock for ' . $itemName . '. Available: ' . $availableQty
                    ], 422);
                }
            }


            $sale = Sale::create([
                'staff_id' => $validated['staff_id'],
                'customer_id' => $validated['customer_id'] ?? null,
                'subtotal' => $validated['subtotal'],
                'discount' => $validated['discount'],
                'total' => $validated['total'],
                'paid_amount' => $validated['paid_amount'],
                'change_amount' => $validated['change_amount'],
                'payment_method' => $validated['payment_method'],
                'status' => 'completed',
            ]);


            foreach ($validated['items'] as $item) {
                $sale->items()->create([
                    'product_id' => $item['productId'],
                    'variant_id' => $item['variantId'] ?? null,
                    'product_name' => $item['productName'],
                    'variant_name' => $item['variantName'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unitPrice'],
                    'subtotal' => $item['subtotal'],
                ]);

                $variant = ProductVariant::find($item['variantId']);
                if ($variant) {
                    $variant->decrement('quantity', $item['quantity']);
                }
            }

            $products = Product::with(['category', 'variants'])
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

            return response()->json(['success' => true, 'sale_id' => $sale->id, 'products' => $products]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
        }
    }
}
