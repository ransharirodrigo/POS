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
                        <h3 class="mb-0">$1,234</h3>
                    </div>
                    <div class="stat-icon stat-icon-primary"><i class="bi bi-currency-dollar"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-1">@lang('messages.dashboard.orders')</h6>
                        <h3 class="mb-0">45</h3>
                    </div>
                    <div class="stat-icon stat-icon-success"><i class="bi bi-bag-check"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-muted mb-1">@lang('messages.dashboard.products')</h6>
                        <h3 class="mb-0">128</h3>
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
                        <h3 class="mb-0">89</h3>
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
                            <tr>
                                <td>#ORD-001</td>
                                <td>John Doe</td>
                                <td>Apr 14, 2026</td>
                                <td>$45.00</td>
                                <td><span class="badge bg-success">@lang('messages.dashboard.completed')</span></td>
                            </tr>
                            <tr>
                                <td>#ORD-002</td>
                                <td>Jane Smith</td>
                                <td>Apr 14, 2026</td>
                                <td>$120.00</td>
                                <td><span class="badge bg-success">@lang('messages.dashboard.completed')</span></td>
                            </tr>
                            <tr>
                                <td>#ORD-003</td>
                                <td>Mike Johnson</td>
                                <td>Apr 14, 2026</td>
                                <td>$75.00</td>
                                <td><span class="badge bg-warning">@lang('messages.dashboard.pending')</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="stat-card">
                <h6 class="mb-3">@lang('messages.dashboard.top_products')</h6>
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <span>Cotton Shirt</span>
                    <span class="badge bg-primary">45 sold</span>
                </div>
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <span>Linen Pants</span>
                    <span class="badge bg-primary">32 sold</span>
                </div>
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <span>Silk Dress</span>
                    <span class="badge bg-primary">28 sold</span>
                </div>
                <div class="d-flex align-items-center justify-content-between py-2">
                    <span>Wool Sweater</span>
                    <span class="badge bg-primary">18 sold</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection