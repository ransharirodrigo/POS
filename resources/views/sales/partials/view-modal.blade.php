<div class="modal fade" id="viewModal{{ $sale->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sale #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Date:</th>
                                <td>{{ $sale->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Staff:</th>
                                <td>{{ $sale->staff->fullName ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Customer:</th>
                                <td>{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                            </tr>
                            <tr>
                                <th>Payment Method:</th>
                                <td><span class="badge bg-info">{{ ucfirst($sale->payment_method) }}</span></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($sale->status == 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($sale->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                            </tr>
                            @if($sale->notes)
                            <tr>
                                <th>Notes:</th>
                                <td>{{ $sale->notes }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Subtotal:</th>
                                <td>Rs.{{ number_format($sale->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Discount:</th>
                                <td>Rs.{{ number_format($sale->discount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td><strong>Rs.{{ number_format($sale->total, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Paid Amount:</th>
                                <td>Rs.{{ number_format($sale->paid_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Change:</th>
                                <td>Rs.{{ number_format($sale->change_amount, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <h6>Sale Items</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Variant</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->variant_name ?? '-' }}</td>
                                <td>Rs.{{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rs.{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>