<div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('messages.categories.edit_category')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('categories.update', $category->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">@lang('messages.categories.name') *</label>
                            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">@lang('messages.categories.description')</label>
                            <textarea name="description" class="form-control" rows="3">{{ $category->description }}</textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active{{ $category->id }}" {{ $category->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active{{ $category->id }}">@lang('messages.categories.is_active')</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('messages.categories.cancel')</button>
                    <button type="submit" class="btn btn-success">@lang('messages.categories.update')</button>
                </div>
            </form>
        </div>
    </div>
</div>
