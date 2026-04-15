<div class="modal fade" id="viewModal{{ $sale->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background: linear-gradient(135deg, #357960, #2d6a4f);">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: rgba(255,255,255,0.2); color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div style="color: white;">
                        <h5 class="modal-title mb-0" style="color: white;">Invoice #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</h5>
                        <small style="color: rgba(255,255,255,0.8);">{{ $sale->created_at->format('d-m-Y H:i') }}</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="info-card h-100">
                            <div class="info-header">
                                <i class="bi bi-person-circle"></i>
                                <span>Transaction Details</span>
                            </div>
                            <div class="info-body">
                                <div class="info-row">
                                    <span class="info-label">Staff</span>
                                    <span class="info-value">{{ $sale->staff->fullName ?? '-' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Customer</span>
                                    <span class="info-value">{{ $sale->customer->name ?? 'Walk-in Customer' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Payment</span>
                                    <span class="info-value">
                                        @if($sale->payment_method == 'cash')
                                            <span class="payment-badge cash"><i class="bi bi-cash"></i> Cash</span>
                                        @elseif($sale->payment_method == 'card')
                                            <span class="payment-badge card"><i class="bi bi-credit-card"></i> Card</span>
                                        @else
                                            <span class="payment-badge online"><i class="bi bi-globe"></i> Online</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Status</span>
                                    <span class="info-value">
                                        @if($sale->status == 'completed')
                                            <span class="status-badge in"><i class="bi bi-check-circle"></i> Completed</span>
                                        @elseif($sale->status == 'pending')
                                            <span class="status-badge low"><i class="bi bi-clock"></i> Pending</span>
                                        @else
                                            <span class="status-badge out"><i class="bi bi-x-circle"></i> Cancelled</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="summary-card h-100">
                            <div class="summary-header">
                                <i class="bi bi-cash-stack"></i>
                                <span>Payment Summary</span>
                            </div>
                            <div class="summary-body">
                                <div class="summary-row">
                                    <span>Subtotal</span>
                                    <span>Rs.{{ number_format($sale->subtotal, 2) }}</span>
                                </div>
                                <div class="summary-row text-danger">
                                    <span>Discount</span>
                                    <span>- Rs.{{ number_format($sale->discount, 2) }}</span>
                                </div>
                                <div class="summary-divider"></div>
                                <div class="summary-row total">
                                    <span>Total</span>
                                    <span>Rs.{{ number_format($sale->total, 2) }}</span>
                                </div>
                                <div class="summary-row">
                                    <span>Paid Amount</span>
                                    <span>Rs.{{ number_format($sale->paid_amount, 2) }}</span>
                                </div>
                                <div class="summary-row change">
                                    <span>Change</span>
                                    <span>Rs.{{ number_format($sale->change_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="items-section">
                    <div class="section-header">
                        <i class="bi bi-bag"></i>
                        <span>Sale Items</span>
                        <span class="badge bg-white text-dark ms-2">{{ $sale->items->count() }}</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table items-table mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Variant</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->items as $item)
                                <tr>
                                    <td>
                                        <div class="product-name">{{ $item->product_name }}</div>
                                    </td>
                                    <td><span class="variant-badge">{{ $item->variant_name ?? '-' }}</span></td>
                                    <td class="text-end">Rs.{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-center"><span class="qty-badge">{{ $item->quantity }}</span></td>
                                    <td class="text-end"><span class="item-total">Rs.{{ number_format($item->subtotal, 2) }}</span></td>
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