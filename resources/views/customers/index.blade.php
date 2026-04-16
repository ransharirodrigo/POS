@extends('layouts.app')

@section('title', __('messages.customers.title'))

@section('content')
<div class="overlay" onclick="closeSidebar()"></div>

@include('partials.sidebar')

<div class="main-content">
    @include('partials.topbar')

    <div class="row g-4">
        <div class="col-12">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">@lang('messages.customers.title')</h5>
                    @canany(['customer add', 'customer manage'])
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-lg me-1"></i> @lang('messages.customers.add_customer')
                    </button>
                    @endcanany
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('messages.customers.name')</th>
                                <th>@lang('messages.customers.phone')</th>
                                <th>@lang('messages.customers.loyalty_points')</th>
                                <th>@lang('messages.customers.is_active')</th>
                                @canany(['customer update', 'customer delete', 'customer manage'])
                                <th>@lang('messages.customers.actions')</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $key => $customer)
                                <tr>
                                    <td>{{ $customers->firstItem() + $key }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->phone ?? '-' }}</td>
                                    <td>{{ $customer->loyalty_points }}</td>
                                    <td>
                                        @if($customer->is_active)
                                            <span class="badge bg-success">@lang('messages.customers.is_active')</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    @canany(['customer update', 'customer delete', 'customer manage'])
                                    <td>
                                        @canany(['customer update', 'customer manage'])
                                        <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $customer->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        @endcanany
                                        @canany(['customer delete', 'customer manage'])
                                        <form id="delete-form-{{ $customer->id }}" action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" data-name='{{ $customer->name }}' onclick="confirmDelete({{ $customer->id }}, this.dataset.name)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        @endcanany
                                    </td>
                                    @endcanany
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">@lang('messages.customers.no_customers')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-start">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@include('customers.partials.create')

@foreach($customers as $customer)
    @include('customers.partials.edit')
@endforeach

@endsection
