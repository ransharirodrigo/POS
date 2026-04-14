@extends('layouts.app')

@section('title', __('messages.categories.title'))

@section('content')
<div class="overlay" onclick="closeSidebar()"></div>

@include('partials.sidebar')

<div class="main-content">
    @include('partials.topbar')

    <div class="row g-4">
        <div class="col-12">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">@lang('messages.categories.title')</h5>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-lg me-1"></i> @lang('messages.categories.add_category')
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('messages.categories.name')</th>
                                <th>@lang('messages.categories.description')</th>
                                <th>@lang('messages.categories.is_active')</th>
                                <th>@lang('messages.categories.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $key => $category)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->description ?? '-' }}</td>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge bg-success">@lang('messages.categories.is_active')</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">@lang('messages.categories.no_categories')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@include('categories.partials.create')

@foreach($categories as $category)
    @include('categories.partials.edit', ['category' => $category])
@endforeach

@endsection
