<div class="modal fade" id="viewModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @if($product->image)
                    <div class="col-md-4 mb-3 mb-md-0">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded">
                    </div>
                    @endif
                    <div class="{{ $product->image ? 'col-md-8' : 'col-12' }}">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">SKU:</th>
                                <td>{{ $product->sku }}</td>
                            </tr>
                            <tr>
                                <th>Base Price:</th>
                                <td>Rs.{{ number_format($product->base_price, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Category:</th>
                                <td>{{ $product->category->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            @if($product->description)
                            <tr>
                                <th>Description:</th>
                                <td>{{ $product->description }}</td>
                            </tr>
                            @endif
                        </table>
                        
                        <h6 class="mt-3">Variants</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Size</th>
                                        <th>Color</th>
                                        <th>SKU</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->variants as $variant)
                                    <tr>
                                        <td>{{ $variant->size }}</td>
                                        <td>{{ $variant->color }}</td>
                                        <td>{{ $variant->sku }}</td>
                                        <td>{{ $variant->quantity }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
