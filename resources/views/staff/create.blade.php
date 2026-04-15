@extends('layouts.app')

@section('title', __('messages.staff.add_staff'))

@section('content')
<div class="overlay" onclick="closeSidebar()"></div>

@include('partials.sidebar')

<div class="main-content">
    @include('partials.topbar')

    <div class="row g-4">
        <div class="col-12">
            <div class="stat-card">
                <h5 class="mb-4">@lang('messages.staff.add_staff')</h5>
                
                <form method="POST" action="{{ route('staff.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.staff.first_name') *</label>
                            <input type="text" name="first_name" class="form-control" required value="{{ old('first_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.staff.last_name') *</label>
                            <input type="text" name="last_name" class="form-control" required value="{{ old('last_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.staff.username') *</label>
                            <input type="text" name="username" class="form-control" required value="{{ old('username') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.staff.phone') *</label>
                            <input type="text" name="phone" class="form-control" maxlength="10" required value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.staff.role') *</label>
                            <select name="role" class="form-select" required>
                                <option value="">Select Role</option>
                                <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>@lang('messages.staff.roles.manager')</option>
                                <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>@lang('messages.staff.roles.cashier')</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.staff.password') *</label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password" onclick="togglePassword(this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">@lang('messages.staff.is_active')</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">@lang('messages.staff.create')</button>
                            <a href="{{ route('staff.index') }}" class="btn btn-secondary">@lang('messages.staff.cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection