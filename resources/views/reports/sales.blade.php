@extends('layouts.app')

@section('title', 'Sales Summary Report')

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
                        <h5 class="mb-1">Sales Summary</h5>
                        <p class="text-muted mb-0 small">Overview of sales performance</p>
                    </div>
                    <form method="GET" class="d-flex gap-2">
                        <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="form-control form-control-sm">
                        <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="form-control form-control-sm">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                    </form>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="stat-box">
                            <div class="stat-icon-primary">
                                <i class="bi bi-cart-check"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-label">Total Orders</span>
                                <h4 class="stat-value mb-0">{{ $totalSales }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box">
                            <div class="stat-icon-success">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-label">Total Revenue</span>
                                <h4 class="stat-value mb-0">Rs.{{ number_format($totalRevenue, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box">
                            <div class="stat-icon-warning">
                                <i class="bi bi-percent"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-label">Total Discount</span>
                                <h4 class="stat-value mb-0">Rs.{{ number_format($totalDiscount, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="report-section">
                            <h6 class="section-title">
                                <i class="bi bi-credit-card me-2"></i>Sales by Payment Method
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless">
                                    <tbody>
                                        @foreach($salesByPayment as $method => $amount)
                                        <tr>
                                            <td>
                                                <span class="payment-badge {{ $method }}">
                                                    <i class="bi bi-{{ $method === 'cash' ? 'cash' : ($method === 'card' ? 'credit-card' : 'wifi') }}"></i>
                                                    {{ ucfirst($method) }}
                                                </span>
                                            </td>
                                            <td class="text-end fw-semibold">Rs.{{ number_format($amount, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="report-section">
                            <h6 class="section-title">
                                <i class="bi bi-person-badge me-2"></i>Sales by Staff
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless">
                                    <thead>
                                        <tr class="text-muted small">
                                            <th>Staff</th>
                                            <th class="text-center">Orders</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($salesByStaff as $staff)
                                        <tr>
                                            <td class="fw-medium">{{ $staff['name'] }}</td>
                                            <td class="text-center"><span class="badge bg-light text-dark">{{ $staff['count'] }}</span></td>
                                            <td class="text-end fw-semibold">Rs.{{ number_format($staff['total'], 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">No sales data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection