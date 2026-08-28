@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <!-- Post Main Content -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h1 class="card-title fw-bold">{{ $post->title }}</h1>
                <p class="text-muted border-bottom pb-2">By {{ $post->user->name }} on {{ $post->created_at->format('M d, Y') }}</p>
                <div class="fs-5 mt-3">{{ $post->body }}</div>
            </div>
        </div>

        <!-- Approved Comments Listing -->
        <h4 class="mb-3">Comments ({{ $post->approvedComments->count() }})</h4>
        @forelse ($post->approvedComments as $comment)
            <div class="card mb-2 bg-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $comment->user->name }}</strong>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-0 text-secondary mt-1">{{ $comment->body }}</p>
                </div>
            </div>
        @empty
            <p class="text-muted">No approved comments yet.</p>
        @endforelse

        <!-- Conditional Form / Login Request -->
        <div class="card mt-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Leave a Comment</h5>
                @auth
                    <form action="{{ route('comments.store', $post) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="body" rows="3" class="form-control @error('body') is-invalid @enderror" placeholder="Write your comment..." required></textarea>
                            @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Comment</button>
                    </form>
                @else
                    <div class="alert alert-warning mb-0">
                        Please <a href="{{ route('login') }}" class="alert-link">login</a> to post a comment.
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection