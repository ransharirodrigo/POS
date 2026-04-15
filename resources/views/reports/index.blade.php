@extends('layouts.app')

@section('title', __('messages.nav.reports'))

@section('content')
<div class="overlay" onclick="closeSidebar()"></div>

@include('partials.sidebar')

<div class="main-content">
    @include('partials.topbar')

    <div class="row g-4">
        <div class="col-12">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Reports</h5>
                    <span class="text-muted">Analytics & Insights</span>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-4">
                        <a href="{{ route('reports.sales') }}" class="text-decoration-none">
                            <div class="report-card h-100">
                                <div class="report-icon bg-primary-subtle">
                                    <i class="bi bi-graph-up-arrow text-primary"></i>
                                </div>
                                <div class="report-content">
                                    <h6>Sales Summary</h6>
                                    <p class="text-muted mb-0">Revenue, orders & payment analysis</p>
                                </div>
                                <i class="bi bi-arrow-right report-arrow"></i>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('reports.top-products') }}" class="text-decoration-none">
                            <div class="report-card h-100">
                                <div class="report-icon bg-warning-subtle">
                                    <i class="bi bi-star text-warning"></i>
                                </div>
                                <div class="report-content">
                                    <h6>Top Products</h6>
                                    <p class="text-muted mb-0">Best selling items by revenue</p>
                                </div>
                                <i class="bi bi-arrow-right report-arrow"></i>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('reports.inventory') }}" class="text-decoration-none">
                            <div class="report-card h-100">
                                <div class="report-icon bg-success-subtle">
                                    <i class="bi bi-box-seam text-success"></i>
                                </div>
                                <div class="report-content">
                                    <h6>Inventory</h6>
                                    <p class="text-muted mb-0">Stock levels & alerts</p>
                                </div>
                                <i class="bi bi-arrow-right report-arrow"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection