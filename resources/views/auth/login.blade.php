@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white text-center">
                    <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=400&q=80" alt="Textile Shop" class="img-fluid rounded mb-2" style="max-height: 120px;">
                    <h3 class="mb-0">@lang('messages.login.title')</h3>
                    <small class="text-white-50">@lang('messages.login.welcome')</small>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="/login">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">@lang('messages.login.username')</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required autofocus>
                            @error('username')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">@lang('messages.login.password')</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            @error('password')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success w-100">@lang('messages.login.button')</button>
                    </form>
                </div>
                <div class="card-footer text-center bg-white border-0">
                    <small class="text-muted">&copy; {{ date('Y') }} @lang('messages.common.all_rights_reserved')</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection