@extends('layouts.app')

@section('title', __('messages.nav.sales'))

@section('content')
<div class="overlay" onclick="closeSidebar()"></div>

@include('partials.sidebar')

<div class="main-content">
    @include('partials.topbar')

    <div class="row g-4">
        <div class="col-12">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">@lang('messages.nav.sales')</h5>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Invoice No</th>
                                <th>Staff</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $key => $sale)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $sale->created_at->format('d-m-Y H:i') }}</td>
                                    <td>{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $sale->staff->fullName ?? '-' }}</td>
                                    <td>{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                                    <td>{{ number_format($sale->total, 2) }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($sale->payment_method) }}</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $sale->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No sales found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>

    @foreach($sales as $sale)
        @include('sales.partials.view-modal', ['sale' => $sale])
    @endforeach
</div>

@endsection