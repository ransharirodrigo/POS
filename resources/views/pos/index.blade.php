@extends('layouts.app')

@section('title', 'POS')

@section('content')
<div class="container-fluid px-4 pos-layout d-flex align-items-center justify-content-center min-vh-100">
    <div class="row g-4 w-100">
        <div class="col-lg-4">
            <div class="stat-card rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-cart-check me-2"></i>@lang('messages.pos.new_sale')</h5>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-sm rounded-2">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">@lang('messages.staff.title') *</label>
                        <select id="saleStaff" class="form-select" required>
                            <option value="">Staff Member</option>
                            @foreach($staff as $s)
                            <option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">@lang('messages.pos.select_customer')</label>
                        <select id="saleCustomer" class="form-select rounded-2">
                            <option value="">@lang('messages.pos.walk_in_customer')</option>
                            @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="border-top pt-3 mb-3">
                    <label class="form-label fw-semibold">@lang('messages.pos.cart_items')</label>
                    <div id="cartItems" class="cart-items-container shadow-sm">
                        <p class="text-muted text-center py-4"><i class="bi bi-cart-x fs-1 d-block mb-2"></i>@lang('messages.pos.no_items')</p>
                    </div>
                </div>

                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>@lang('messages.pos.subtotal'):</span>
                        <span id="cartSubtotal">Rs. 0.00</span>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">@lang('messages.pos.discount')</label>
                            <input type="number" id="cartDiscount" class="form-control form-control-sm" value="0" min="0" step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-6 text-end">
                            <label class="form-label fw-semibold small">@lang('messages.pos.total')</label>
                            <div class="h5 mb-0 fw-bold text-success" id="cartTotal">Rs. 0.00</div>
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">@lang('messages.pos.paid_amount')</label>
                            <input type="number" id="paidAmount" class="form-control form-control-sm" value="0" min="0" step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-6 text-end">
                            <label class="form-label fw-semibold small">@lang('messages.pos.change')</label>
                            <div class="h5 mb-0 fw-bold text-success" id="changeAmount">Rs. 0.00</div>
                        </div>
                    </div>
                    <input type="hidden" id="paymentMethod" value="cash">
                    <button type="button" id="completeSale" class="btn btn-success w-100" disabled>
                        <i class="bi bi-check-circle me-1"></i> @lang('messages.pos.complete_sale')
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="stat-card rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2"></i>@lang('messages.pos.products')</h5>
                    <div class="d-flex gap-2">
                        <select id="categoryFilter" class="form-select form-select-sm rounded-2" style="width: 150px;">
                            <option value="">@lang('messages.pos.all_categories')</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" id="productSearch" class="form-control form-control-sm rounded-2" placeholder="@lang('messages.pos.search_products')" style="width: 200px;">
                    </div>
                </div>
                
                <div class="row g-3" id="productGrid">
                    @forelse($products as $product)
                    <div class="col-6 col-md-4 col-xl-3">
                        @php($onclick = $product->variants->count() ? 'showVariants(' . $product->id . ')' : "addToCart(" . $product->id . ", null, " . $product->base_price . ", '" . addslashes($product->name) . "', null, " . $product->base_price . ")")
                        <div class="card product-card h-100 border-0 shadow-sm rounded-3" onclick="{{ $onclick }}">
                            @if($product->image)
                            <img src="/storage/{{ $product->image }}" class="card-img-top rounded-top-3" alt="{{ $product->name }}" style="height: 100px; object-fit: cover;">
                            @else
                            <div class="product-image-placeholder d-flex align-items-center justify-content-center bg-light rounded-top-3" style="height: 100px;"><i class="bi bi-image text-muted" style="font-size: 2rem;"></i></div>
                            @endif
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1 fw-semibold">{{ $product->name }}</h6>
                                <p class="product-price mb-1 fw-bold">Rs. {{ number_format($product->base_price, 2) }}</p>
                                @if($product->category)
                                <small class="badge bg-light text-dark">{{ $product->category->name }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted p-3">@lang('messages.pos.no_products')</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
var products = @json($products);
var cart = [];
</script>
<script src="{{ asset('js/pos.js') }}"></script>
@endsection
