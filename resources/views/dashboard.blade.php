@extends('layouts.app')

@section('title', __('messages.nav.dashboard'))

@section('content')
<div class="overlay" onclick="closeSidebar()"></div>

@include('partials.sidebar')

<div class="main-content">
    @include('partials.topbar')

    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-1">@lang('messages.dashboard.todays_sales')</h6>
                        <h3 class="mb-0">Rs.{{ number_format($todayRevenue, 2) }}</h3>
                        <small class="text-muted">{{ $todaySales }} orders</small>
                    </div>
                    <div class="stat-icon stat-icon-primary"><i class="bi bi-currency-dollar"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-1">This Month</h6>
                        <h3 class="mb-0">Rs.{{ number_format($monthRevenue, 2) }}</h3>
                        <small class="text-muted">{{ $monthSales }} orders</small>
                    </div>
                    <div class="stat-icon stat-icon-success"><i class="bi bi-graph-up"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-1">@lang('messages.dashboard.products')</h6>
                        <h3 class="mb-0">{{ $totalProducts }}</h3>
                    </div>
                    <div class="stat-icon stat-icon-warning"><i class="bi bi-box-seam"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-1">@lang('messages.dashboard.customers')</h6>
                        <h3 class="mb-0">{{ $totalCustomers }}</h3>
                    </div>
                    <div class="stat-icon stat-icon-info"><i class="bi bi-people"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="stat-card">
                <h6 class="mb-3">@lang('messages.dashboard.recent_sales')</h6>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>@lang('messages.dashboard.order_id')</th>
                                <th>@lang('messages.dashboard.customer')</th>
                                <th>@lang('messages.dashboard.date')</th>
                                <th>@lang('messages.dashboard.amount')</th>
                                <th>@lang('messages.dashboard.status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                            <tr>
                                <td>#{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $sale->customer->name ?? 'Walk-in' }}</td>
                                <td>{{ $sale->created_at->format('M d, Y') }}</td>
                                <td>Rs.{{ number_format($sale->total, 2) }}</td>
                                <td>
                                    @if($sale->status === 'completed')
                                    <span class="badge bg-success">@lang('messages.dashboard.completed')</span>
                                    @elseif($sale->status === 'pending')
                                    <span class="badge bg-warning">@lang('messages.dashboard.pending')</span>
                                    @else
                                    <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No recent sales</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="stat-card">
                <h6 class="mb-3">@lang('messages.dashboard.top_products')</h6>
                @forelse($topProducts as $product)
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <span>{{ $product->product_name }}</span>
                    <span class="badge bg-primary">{{ $product->total_sold }} sold</span>
                </div>
                @empty
                <p class="text-muted text-center py-3">No sales data</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection