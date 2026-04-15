@extends('layouts.app')

@section('title', 'Top Products Report')

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
                        <h5 class="mb-1">Top Selling Products</h5>
                        <p class="text-muted mb-0 small">Best performing products by revenue</p>
                    </div>
                    <form method="GET" class="d-flex gap-2 align-items-center">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">From</span>
                            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="form-control">
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">To</span>
                            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="form-control">
                        </div>
                        <select name="limit" class="form-select form-select-sm" style="width: auto;">
                            <option value="5" {{ $limit == 5 ? 'selected' : '' }}>Top 5</option>
                            <option value="10" {{ $limit == 10 ? 'selected' : '' }}>Top 10</option>
                            <option value="20" {{ $limit == 20 ? 'selected' : '' }}>Top 20</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="60">#</th>
                                <th>Product Name</th>
                                <th class="text-center">Qty Sold</th>
                                <th class="text-end">Revenue</th>
                                <th width="100">Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $key => $product)
                            <tr>
                                <td>
                                    <span class="rank-badge top-{{ $key + 1 }}"> {{ $key + 1 }} </span>
                                </td>
                                <td class="fw-medium">{{ $product->product_name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark">{{ $product->total_qty }}</span>
                                </td>
                                <td class="text-end fw-bold text-success">Rs.{{ number_format($product->total_sales, 2) }}</td>
                                <td>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-success"></div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No sales data available</td>
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