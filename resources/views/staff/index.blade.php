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

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
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
                            @forelse($employees as $key => $employee)
                                <tr>
                                    <td>{{ $employees->firstItem() + $key }}</td>
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
                                        <form id="delete-form-{{ $employee->id }}" action="{{ route('staff.destroy', $employee->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteStaff({{ $employee->id }}, '{{ $employee->first_name }} {{ $employee->last_name }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">@lang('messages.staff.no_staff')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($employees as $employee)
    @include('staff.partials.edit-modal')
@endforeach

@endsection
