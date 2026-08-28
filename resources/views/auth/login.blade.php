@extends('layouts.app')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-5 col-lg-4">
        <div class="custom-card shadow-sm p-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold">Welcome Back</h3>
                <p class="text-muted small">Sign in to leave comments and interact</p>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-custom @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Password</label>
                    <input type="password" name="password" class="form-control form-control-custom @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-accent w-100 mt-2">Sign In</button>
            </form>
            <p class="text-center text-muted small mt-4 mb-0">
                Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color: var(--primary-accent);">Register</a>
            </p>
        </div>
    </div>
</div>
@endsection