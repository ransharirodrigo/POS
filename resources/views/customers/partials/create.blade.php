<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('messages.customers.add_customer')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('customers.store') }}" id="createCustomerForm">
                @csrf
                <div class="modal-body">
                    <div id="createFormErrors" class="alert alert-danger d-none"></div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">@lang('messages.customers.name') *</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">@lang('messages.customers.phone') *</label>
                            <input type="text" name="phone" class="form-control" required value="{{ old('phone') }}">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">@lang('messages.customers.is_active')</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('messages.common.cancel')</button>
                    <button type="submit" class="btn btn-success">@lang('messages.common.save')</button>
                </div>
            </form>
        </div>
    </div>
</div>
