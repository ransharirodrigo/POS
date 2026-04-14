<div class="modal fade" id="editModal{{ $employee->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('messages.staff.edit_staff')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('staff.update', $employee->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">@lang('messages.staff.first_name')</label>
                            <input type="text" name="first_name" class="form-control" value="{{ $employee->first_name }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">@lang('messages.staff.last_name')</label>
                            <input type="text" name="last_name" class="form-control" value="{{ $employee->last_name }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">@lang('messages.staff.username')</label>
                            <input type="text" name="username" class="form-control" value="{{ $employee->username }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">@lang('messages.staff.phone')</label>
                            <input type="text" name="phone" class="form-control" maxlength="10" value="{{ $employee->phone }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">@lang('messages.staff.role')</label>
                            <select name="role" class="form-select" disabled>
                                <option value="">-- Select Role --</option>
                                <option value="super admin" {{ $employee->role == 'super admin' ? 'selected' : '' }}>@lang('messages.staff.roles.super admin')</option>
                                <option value="manager" {{ $employee->role == 'manager' ? 'selected' : '' }}>@lang('messages.staff.roles.manager')</option>
                                <option value="cashier" {{ $employee->role == 'cashier' ? 'selected' : '' }}>@lang('messages.staff.roles.cashier')</option>
                            </select>
                            <input type="hidden" name="role" value="{{ $employee->role }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">@lang('messages.staff.password')</label>
                            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active{{ $employee->id }}" {{ $employee->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active{{ $employee->id }}">@lang('messages.staff.is_active')</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('messages.staff.cancel')</button>
                    <button type="submit" class="btn btn-success">@lang('messages.staff.update')</button>
                </div>
            </form>
        </div>
    </div>
</div>