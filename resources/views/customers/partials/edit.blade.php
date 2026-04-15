<div class="modal fade" id="editModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('messages.customers.edit_customer')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('customers.update', $customer->id) }}" id="editCustomerForm{{ $customer->id }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div id="editFormErrors{{ $customer->id }}" class="alert alert-danger d-none"></div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">@lang('messages.customers.name') *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">@lang('messages.customers.phone') *</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}" required>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active{{ $customer->id }}" value="1" {{ old('is_active', $customer->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active{{ $customer->id }}">@lang('messages.customers.is_active')</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('messages.common.cancel')</button>
                    <button type="submit" class="btn btn-success">@lang('messages.common.update')</button>
                </div>
            </form>
        </div>
    </div>
</div>
