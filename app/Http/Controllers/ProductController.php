<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CanDelete;
use App\Http\Controllers\Traits\ImageUploadTrait;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    use ImageUploadTrait, CanDelete;

    public function index()
    {
        Gate::authorize('product view');
        $products = Product::with('variants')->orderBy('id', 'desc')->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        Gate::authorize('product add');
        return view('products.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('product add');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'base_price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'variants' => 'required|array|min:1',
            'variants.*.size' => 'required|string|max:20',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.sku' => 'required|string|max:50|unique:product_variants,sku',
            'variants.*.quantity' => 'required|integer|min:0',
        ], [
            'name.required' => 'Please enter the product name.',
            'sku.required' => 'Please enter the product SKU.',
            'sku.unique' => 'This product SKU has already been taken. Please use a different SKU.',
            'base_price.required' => 'Please enter the product price.',
            'image.max' => 'The image is too large. Please upload an image smaller than 2MB.',
            'variants.*.sku.unique' => 'This SKU has already been taken. Please use a different SKU.',
            'variants.*.size.required' => 'Please select a size for this variant.',
            'variants.*.color.required' => 'Please enter a color for this variant.',
            'variants.*.sku.required' => 'Please enter a SKU for this variant.',
            'variants.*.quantity.required' => 'Please enter the quantity for this variant.',
        ]);

        $product = DB::transaction(function () use ($validated, $request) {
            $imagePath = $this->handleImageUpload($request, null, 'public', 'products');

            $product = Product::create([
                'name' => $validated['name'],
                'sku' => $validated['sku'],
                'description' => $validated['description'] ?? null,
                'image' => $imagePath,
                'base_price' => $validated['base_price'],
                'category_id' => $validated['category_id'] ?? null,
                'is_active' => $request->has('is_active'),
            ]);

            foreach ($validated['variants'] as $variant) {
                $product->variants()->create([
                    'size' => $variant['size'],
                    'color' => $variant['color'],
                    'sku' => $variant['sku'],
                    'price' => $validated['base_price'],
                    'quantity' => $variant['quantity'],
                    'is_active' => true,
                ]);
            }

            return $product;
        });

        return redirect()->route('products.index')->with('success', __('messages.products.created'));
    }

    public function edit(Product $product)
    {
        Gate::authorize('product update');
        $product->load('variants');
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        Gate::authorize('product update');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'base_price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'variants' => 'required|array|min:1',
            'variants.*.size' => 'required|string|max:20',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.sku' => 'required|string|max:50',
            'variants.*.quantity' => 'required|integer|min:0',
        ], [
            'name.required' => 'Please enter the product name.',
            'sku.required' => 'Please enter the product SKU.',
            'sku.unique' => 'This product SKU has already been taken. Please use a different SKU.',
            'base_price.required' => 'Please enter the product price.',
            'image.max' => 'The image is too large. Please upload an image smaller than 2MB.',
            'variants.*.size.required' => 'Please select a size for this variant.',
            'variants.*.color.required' => 'Please enter a color for this variant.',
            'variants.*.sku.required' => 'Please enter a SKU for this variant.',
            'variants.*.quantity.required' => 'Please enter the quantity for this variant.',
        ]);

        DB::transaction(function () use ($validated, $request, $product) {
            $imagePath = $this->handleImageUpload($request, $product->image, 'public', 'products');

            $product->update([
                'name' => $validated['name'],
                'sku' => $validated['sku'],
                'description' => $validated['description'] ?? null,
                'image' => $imagePath,
                'base_price' => $validated['base_price'],
                'category_id' => $validated['category_id'] ?? null,
                'is_active' => $request->has('is_active'),
            ]);

            $existingIds = $product->variants()->pluck('id')->toArray();
            $incomingIds = [];

            foreach ($validated['variants'] as $variantData) {
                $variantId = isset($variantData['id']) && $variantData['id'] !== '' && $variantData['id'] !== null
                    ? (int) $variantData['id']
                    : null;

                if (!is_null($variantId)) {
                    $variant = ProductVariant::find($variantId);
                    if ($variant && $variant->product_id === $product->id) {
                        $variant->update([
                            'size' => $variantData['size'],
                            'color' => $variantData['color'],
                            'sku' => $variantData['sku'],
                            'price' => $validated['base_price'],
                            'quantity' => $variantData['quantity'],
                        ]);
                        $incomingIds[] = $variant->id;
                    }
                } else {
                
                    $existingVariant = ProductVariant::where('product_id', $product->id)
                        ->where('size', $variantData['size'])
                        ->where('color', $variantData['color'])
                        ->first();

                    if ($existingVariant) {
                    
                        $existingVariant->update([
                            'sku' => $variantData['sku'],
                            'price' => $validated['base_price'],
                            'quantity' => $variantData['quantity'],
                        ]);
                        $incomingIds[] = $existingVariant->id;
                    } else {
                    
                        $newVariant = $product->variants()->create([
                            'size' => $variantData['size'],
                            'color' => $variantData['color'],
                            'sku' => $variantData['sku'],
                            'price' => $validated['base_price'],
                            'quantity' => $variantData['quantity'],
                            'is_active' => true,
                        ]);
                        $incomingIds[] = $newVariant->id;
                    }
                }
            }

            $toDelete = array_diff($existingIds, $incomingIds);
            if (!empty($toDelete)) {
                ProductVariant::destroy($toDelete);
            }
        });

        return redirect()->route('products.index')->with('success', __('messages.products.updated'));
    }

    public function destroy(Product $product)
    {
        Gate::authorize('product delete');
        
        return $this->deleteWithQuery(
            $product,
            function ($model) {
                return $model->saleItems();
            },
            'products.index',
            'products',
            __('messages.products.deleted'),
            function ($model) {
                $this->deleteImage($model->image, 'public');
            }
        );
    }
}
