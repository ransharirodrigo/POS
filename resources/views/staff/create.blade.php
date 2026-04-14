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
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.staff.last_name') *</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.staff.username') *</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.staff.phone') *</label>
                            <input type="text" name="phone" class="form-control" maxlength="10" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.staff.role') *</label>
                            <select name="role" class="form-select" required>
                                <option value="">Select Role</option>
                                <option value="super admin">@lang('messages.staff.roles.super admin')</option>
                                <option value="manager">@lang('messages.staff.roles.manager')</option>
                                <option value="cashier">@lang('messages.staff.roles.cashier')</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.staff.password') *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
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