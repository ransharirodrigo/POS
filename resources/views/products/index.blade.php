@extends('layouts.app')

@section('title', __('messages.products.title'))

@section('content')
<div class="overlay" onclick="closeSidebar()"></div>

@include('partials.sidebar')

<div class="main-content">
    @include('partials.topbar')

    <div class="row g-4">
        <div class="col-12">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">@lang('messages.products.title')</h5>
                    <a href="{{ route('products.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-lg me-1"></i> @lang('messages.products.add_product')
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('messages.products.image')</th>
                                <th>@lang('messages.products.name')</th>
                                <th>@lang('messages.products.sku')</th>
                                <th>@lang('messages.products.base_price')</th>
                                <th>@lang('messages.products.variants')</th>
                                <th>@lang('messages.products.is_active')</th>
                                <th>@lang('messages.products.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $key => $product)
                                <tr>
                                    <td>{{ $products->firstItem() + $key }}</td>
                                    <td>
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-width: 50px; max-height: 50px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal{{ $product->id }}">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->sku }}</td>
                                    <td>Rs.{{ number_format($product->base_price, 2) }}</td>
                                    <td>
                                        @foreach($product->variants as $variant)
                                            <span class="badge bg-secondary me-1">
                                                {{ $variant->size }}/{{ $variant->color }} ({{ $variant->quantity }})
                                            </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success">@lang('messages.products.is_active')</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#viewModal{{ $product->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">@lang('messages.products.no_products')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-start">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($products as $product)
    @include('products.partials.view-modal', ['product' => $product])
    @include('products.partials.image-modal', ['product' => $product])
@endforeach

@endsection