@extends('layouts.app')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-6 col-lg-5">
        <div class="custom-card shadow-sm p-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold">Create an Account</h3>
                <p class="text-muted small">Join the community to share and discuss articles</p>
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Full Name</label>
                    <input type="text" name="name" class="form-control form-control-custom @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-custom @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Password</label>
                    <input type="password" name="password" class="form-control form-control-custom @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control form-control-custom" required>
                </div>
                <button type="submit" class="btn btn-accent w-100 mt-2">Create Account</button>
            </form>
            <p class="text-center text-muted small mt-4 mb-0">
                Already registered? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color: var(--primary-accent);">Login</a>
            </p>
        </div>
    </div>
</div>
@endsection