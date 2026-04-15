@extends('layouts.app')

@section('title', __('messages.products.edit_product'))

@section('content')
<div class="overlay" onclick="closeSidebar()"></div>

@include('partials.sidebar')

<div class="main-content">
    @include('partials.topbar')

    <div class="row g-4">
        <div class="col-12">
            <div class="stat-card">
                <h5 class="mb-4">@lang('messages.products.edit_product') - {{ $product->name }}</h5>
                
                <form method="POST" action="{{ route('products.update', $product->id) }}" id="productForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.products.name') *</label>
                            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.products.sku') *</label>
                            <input type="text" name="sku" class="form-control" value="{{ $product->sku }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.products.base_price') *</label>
                            <input type="number" name="base_price" class="form-control" value="{{ $product->base_price }}" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.products.category')</label>
                            <select name="category_id" class="form-select">
                                <option value="">-- Select Category --</option>
                                @foreach(\App\Models\Category::where('is_active', true)->get() as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">@lang('messages.products.description')</label>
                            <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">@lang('messages.products.image')</label>
                            <input type="file" name="image" id="imageInput" class="form-control" accept="image/*">
                            <small class="text-muted">Max: 2MB (JPEG, PNG, JPG, GIF)</small>
                            @if($product->image)
                                <div class="mt-2" id="currentImageContainer">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-width: 200px; max-height: 200px; object-fit: cover;" id="currentImage" class="img-thumbnail">
                                </div>
                            @endif
                            <div id="imagePreviewContainer" class="mt-2" style="display: none;">
                                <img id="imagePreview" src="" alt="Product Image" class="img-thumbnail" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                            </div>
                        </div>
                        
                        <div class="col-12 mt-4">
                            <h6 class="mb-3">@lang('messages.products.variants') (Size & Color)</h6>
                            <div id="variants-container">
                                @foreach($product->variants as $index => $variant)
                                <div class="variant-row row g-3 mb-3">
                                    <div class="col-md-3">
                                        <select name="variants[{{ $index }}][size]" class="form-select" required>
                                            <option value="">-- Select Size --</option>
                                            @foreach($sizes as $size)
                                                <option value="{{ $size }}" {{ $variant->size == $size ? 'selected' : '' }}>{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="variants[{{ $index }}][color]" class="form-control" value="{{ $variant->color }}" placeholder="Color" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="variants[{{ $index }}][sku]" class="form-control" value="{{ $variant->sku }}" placeholder="Variant SKU" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="variants[{{ $index }}][quantity]" class="form-control" value="{{ $variant->quantity }}" placeholder="Quantity" min="0" required>
                                    </div>
                                    <div class="col-md-1">
                                        <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                        @if($index === 0)
                                        <button type="button" class="btn btn-danger remove-variant" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-danger remove-variant">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary" id="add-variant">
                                <i class="bi bi-plus-lg me-1"></i> Add Variant
                            </button>
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ $product->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">@lang('messages.products.is_active')</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">@lang('messages.products.update')</button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">@lang('messages.products.cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const sizes = @json($sizes);

document.getElementById('imageInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('imagePreviewContainer');
    const preview = document.getElementById('imagePreview');
    const currentImageContainer = document.getElementById('currentImageContainer');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
            if (currentImageContainer) {
                currentImageContainer.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
        preview.src = '';
        if (currentImageContainer) {
            currentImageContainer.style.display = 'block';
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    let variantCount = {{ $product->variants->count() }};
    const container = document.getElementById('variants-container');
    const addBtn = document.getElementById('add-variant');

    function getSizeOptions() {
        return '<option value="">-- Select Size --</option>' + sizes.map(s => `<option value="${s}">${s}</option>`).join('');
    }

    addBtn.addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'variant-row row g-3 mb-3';
        row.innerHTML = `
            <div class="col-md-3">
                <select name="variants[${variantCount}][size]" class="form-select" required>
                    ${getSizeOptions()}
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="variants[${variantCount}][color]" class="form-control" placeholder="Color" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="variants[${variantCount}][sku]" class="form-control" placeholder="Variant SKU" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="variants[${variantCount}][quantity]" class="form-control" placeholder="Quantity" min="0" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger remove-variant">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
        variantCount++;
        updateRemoveButtons();
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-variant')) {
            e.target.closest('.variant-row').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const rows = container.querySelectorAll('.variant-row');
        const buttons = container.querySelectorAll('.remove-variant');
        buttons.forEach((btn, index) => {
            btn.disabled = rows.length <= 1;
        });
    }
});
</script>

@endsection