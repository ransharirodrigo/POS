@extends('layouts.app')

@section('title', __('messages.staff.title'))

@section('content')
<div class="overlay" onclick="closeSidebar()"></div>

@include('partials.sidebar')

<div class="main-content">
    @include('partials.topbar')

    <div class="row g-4">
        <div class="col-12">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">@lang('messages.staff.title')</h5>
                    <a href="{{ route('staff.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-lg me-1"></i> @lang('messages.staff.add_staff')
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>@lang('messages.staff.first_name')</th>
                                <th>@lang('messages.staff.last_name')</th>
                                <th>@lang('messages.staff.username')</th>
                                <th>@lang('messages.staff.phone')</th>
                                <th>@lang('messages.staff.role')</th>
                                <th>@lang('messages.staff.is_active')</th>
                                <th>@lang('messages.staff.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                <tr>
                                    <td>{{ $employee->id }}</td>
                                    <td>{{ $employee->first_name }}</td>
                                    <td>{{ $employee->last_name }}</td>
                                    <td>{{ $employee->username }}</td>
                                    <td>{{ $employee->phone }}</td>
                                    <td>{{ __("messages.staff.roles." . $employee->role) }}</td>
                                    <td>
                                        @if($employee->is_active)
                                            <span class="badge bg-success">@lang('messages.staff.is_active')</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $employee->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('staff.destroy', $employee->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('messages.staff.confirm_delete') }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">@lang('messages.staff.no_staff')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($employees as $employee)
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
@endforeach

@endsection