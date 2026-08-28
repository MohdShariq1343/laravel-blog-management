@extends('layouts.app')

@section('content')
<div class="row justify-content-center py-3">
    <div class="col-lg-9">
        <!-- Main Article Container -->
        <article class="custom-card p-4 p-md-5 mb-5 shadow-sm">
            <header class="mb-4 pb-3 border-bottom">
                <h1 class="fw-extrabold text-dark display-6 mb-3">{{ $post->title }}</h1>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-circle">
                        {{ strtoupper(substr($post->author_name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-semibold text-dark">{{ $post->author_name }}</div>
                        <div class="text-muted small">{{ \Carbon\Carbon::parse($post->created_at)->format('M d, Y') }}</div>
                    </div>
                </div>
            </header>
            <div class="article-body fs-5 text-secondary leading-relaxed mb-0" style="line-height: 1.8;">
                {!! nl2br(e($post->body)) !!}
            </div>
        </article>

        <!-- New Comment Box -->
        <div class="custom-card p-4 mb-5 shadow-sm">
            <h5 class="fw-bold mb-3">Join the Conversation</h5>
            @auth
                <form action="{{ route('comments.store', $post->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea name="body" rows="3" class="form-control form-control-custom" placeholder="Write your thoughts..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-accent btn-sm px-4">Post Comment</button>
                </form>
            @else
                <div class="p-3 bg-light rounded-3 text-center">
                    <span class="text-muted">Please <a href="{{ route('login') }}" class="fw-semibold text-decoration-none" style="color: var(--primary-accent);">log in</a> to participate in discussions.</span>
                </div>
            @endauth
        </div>

        <!-- Comments Stream -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h4 class="fw-bold mb-0">Comments</h4>
            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fs-6">{{ count($post->comments) }}</span>
        </div>

        @forelse ($post->comments as $comment)
            <div class="custom-card p-4 mb-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-circle" style="width: 32px; height: 32px; font-size: 0.8rem;">
                            {{ strtoupper(substr($comment->user_name, 0, 1)) }}
                        </div>
                        <span class="fw-semibold text-dark">{{ $comment->user_name }}</span>
                    </div>
                    <span class="text-muted small">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                </div>
                <p class="text-secondary my-2 ms-4 ps-2">{{ $comment->body }}</p>

                <!-- Reply Actions -->
                @auth
                    <div class="ms-4 ps-2 mt-2">
                        <button class="btn btn-link btn-sm p-0 text-decoration-none fw-semibold" style="color: var(--primary-accent);" type="button" data-bs-toggle="collapse" data-bs-target="#reply-{{ $comment->id }}">
                            Reply
                        </button>
                        
                        <div class="collapse mt-3" id="reply-{{ $comment->id }}">
                            <form action="{{ route('comments.store', $post->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                <div class="mb-2">
                                    <textarea name="body" rows="2" class="form-control form-control-custom form-control-sm" placeholder="Write a reply..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-accent btn-sm">Submit Reply</button>
                            </form>
                        </div>
                    </div>
                @endauth

                <!-- Nested Replies Array -->
                @if(count($comment->replies) > 0)
                    <div class="ms-4 ps-3 border-start mt-3">
                        @foreach ($comment->replies as $reply)
                            <div class="p-3 bg-light rounded-3 mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="small text-dark">{{ $reply->user_name }}</strong>
                                    <span class="text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($reply->created_at)->diffForHumans() }}</span>
                                </div>
                                <p class="mb-0 small text-secondary">{{ $reply->body }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-4 text-muted">No comments yet.</div>
        @endforelse
    </div>
</div>
@endsection