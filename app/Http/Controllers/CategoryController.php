<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CanDelete;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    use CanDelete;
    public function index()
    {
        Gate::authorize('category view');
        $categories = Category::orderBy('id', 'desc')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        Gate::authorize('category add');
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('categories.index')->with('success', __('messages.categories.created'));
    }

    public function update(Request $request, Category $category)
    {
        Gate::authorize('category update');
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('categories.index')->with('success', __('messages.categories.updated'));
    }

    public function destroy(Category $category)
    {
        Gate::authorize('category delete');
        
        return $this->deleteIfNoRelated($category, 'products', 'categories.index', 'categories', __('messages.categories.deleted'));
    }
}