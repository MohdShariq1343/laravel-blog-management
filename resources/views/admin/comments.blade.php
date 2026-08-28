@extends('layouts.app')

@section('content')
<div class="row justify-content-center py-4">
    <div class="col-lg-10">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold mb-1">Comment Moderation Queue</h3>
                <p class="text-muted small mb-0">Approve user comments before they appear on live posts</p>
            </div>
            <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill">{{ count($pendingComments) }} Pending</span>
        </div>

        @forelse ($pendingComments as $comment)
            <div class="custom-card p-4 mb-3 border-start border-4 border-warning shadow-sm">
                <div class="row align-items-center">
                    <div class="col-md-9 mb-3 mb-md-0">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-light text-dark border">{{ $comment->user_name }}</span>
                            <span class="text-muted small">on article:</span>
                            <a href="{{ route('posts.show', $comment->post_slug) }}" class="fw-semibold text-decoration-none text-dark">{{ $comment->post_title }}</a>
                        </div>
                        <p class="mb-1 text-secondary fs-6">{{ $comment->body }}</p>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</small>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <form action="{{ route('admin.comments.approve', $comment->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm px-4 rounded-pill fw-semibold">
                                Approve
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="custom-card p-5 text-center shadow-sm">
                <div class="text-success fs-1 mb-2">✓</div>
                <h5 class="fw-bold">Queue Clear!</h5>
                <p class="text-muted mb-0 small">All user comments have been moderated.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection