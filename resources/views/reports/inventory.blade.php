@extends('layouts.app')

@section('title', 'Inventory Report')

@section('content')
<div class="overlay" onclick="closeSidebar()"></div>

@include('partials.sidebar')

<div class="main-content">
    @include('partials.topbar')

    <div class="row g-4 mb-3">
        <div class="col-12">
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="mb-1">Inventory Overview</h5>
                        <p class="text-muted mb-0 small">Stock levels and alerts</p>
                    </div>
                    <form method="GET" class="d-flex gap-2">
                        <select name="category_id" class="form-select form-select-sm" style="width: 150px;">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                    </form>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="alert-box alert-danger">
                            <div class="alert-icon">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                            <div class="alert-content">
                                <span class="alert-label">Out of Stock</span>
                                <h4 class="alert-value mb-0">{{ $outOfStockProducts->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert-box alert-warning">
                            <div class="alert-icon">
                                <i class="bi bi-exclamation-circle-fill"></i>
                            </div>
                            <div class="alert-content">
                                <span class="alert-label">Low Stock</span>
                                <h4 class="alert-value mb-0">{{ $lowStockProducts->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert-box alert-success">
                            <div class="alert-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="alert-content">
                                <span class="alert-label">Total Products</span>
                                <h4 class="alert-value mb-0">{{ $products->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                @if($outOfStockProducts->count() > 0)
                <div class="mb-4">
                    <h6 class="section-title text-danger">
                        <i class="bi bi-x-circle me-2"></i>Out of Stock ({{ $outOfStockProducts->count() }})
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th class="text-center">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($outOfStockProducts as $product)
                                <tr class="table-danger">
                                    <td class="fw-medium">{{ $product['name'] }}</td>
                                    <td><code>{{ $product['sku'] }}</code></td>
                                    <td>{{ $product['category'] }}</td>
                                    <td class="text-center"><span class="badge bg-danger">0</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                @if($lowStockProducts->count() > 0)
                <div class="mb-4">
                    <h6 class="section-title text-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>Low Stock - Below 10 Units ({{ $lowStockProducts->count() }})
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th class="text-center">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $product)
                                <tr class="table-warning">
                                    <td class="fw-medium">{{ $product['name'] }}</td>
                                    <td><code>{{ $product['sku'] }}</code></td>
                                    <td>{{ $product['category'] }}</td>
                                    <td class="text-center"><span class="badge bg-warning text-dark">{{ $product['total_stock'] }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <div>
                    <h6 class="section-title">
                        <i class="bi bi-box-seam me-2"></i>All Products
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th class="text-center">Total Stock</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr class="{{ $product['total_stock'] == 0 ? 'table-danger' : ($product['total_stock'] < 10 ? 'table-warning' : '') }}">
                                    <td class="fw-medium">{{ $product['name'] }}</td>
                                    <td><code>{{ $product['sku'] }}</code></td>
                                    <td>{{ $product['category'] }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $product['total_stock'] > 0 ? 'bg-light text-dark' : 'bg-danger' }}">
                                            {{ $product['total_stock'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($product['total_stock'] == 0)
                                        <span class="status-badge out">Out of Stock</span>
                                        @elseif($product['total_stock'] < 10)
                                        <span class="status-badge low">Low Stock</span>
                                        @else
                                        <span class="status-badge in">In Stock</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No products found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
</div>
</div>
@endsection