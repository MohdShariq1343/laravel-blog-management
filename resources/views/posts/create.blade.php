@extends('layouts.app')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-md-8">
        <div class="custom-card shadow-sm p-4">
            <div class="border-bottom pb-3 mb-4">
                <h3 class="fw-bold mb-1">Create New Article</h3>
                <p class="text-muted small mb-0">Publish content to your blog platform</p>
            </div>
            <form action="{{ route('posts.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-semibold">Article Title</label>
                    <input type="text" name="title" class="form-control form-control-custom @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g. Getting Started with Laravel 12" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Body Content</label>
                    <textarea name="body" rows="8" class="form-control form-control-custom @error('body') is-invalid @enderror" placeholder="Write your post content here..." required>{{ old('body') }}</textarea>
                    @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('posts.index') }}" class="btn btn-light px-4 border">Cancel</a>
                    <button type="submit" class="btn btn-accent px-4">Publish Article</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection