@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        
        <!-- Hero Header & Search Section -->
        <div class="mb-5 text-center text-lg-start">
            <h1 class="fw-extrabold display-5 mb-2 text-dark">Blog Feed</h1>
            <p class="text-muted fs-6 mb-4">Discover thought-provoking articles, tutorials, and community insights.</p>
            
            <form action="{{ route('posts.index') }}" method="GET" class="d-flex gap-2">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 pe-0 rounded-start-3" style="border-color: var(--card-border);">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="text-muted">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-custom border-start-0 ps-2" placeholder="Search by title or content...">
                </div>
                <button type="submit" class="btn btn-accent px-4">Search</button>
                @if(request('search'))
                    <a href="{{ route('posts.index') }}" class="btn btn-light border px-3 d-flex align-items-center">Clear</a>
                @endif
            </form>
        </div>

        <!-- Post List Container -->
        @forelse ($posts as $post)
            <article class="custom-card custom-card-hover p-4 mb-4 shadow-sm">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="avatar-circle" style="width: 36px; height: 36px; font-size: 0.85rem;">
                        {{ strtoupper(substr($post->author_name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-semibold text-dark small">{{ $post->author_name }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">
                            {{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}
                        </div>
                    </div>
                </div>

                <h2 class="h4 fw-bold mb-2">
                    <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none text-dark hover-primary">
                        {{ $post->title }}
                    </a>
                </h2>

                <p class="text-secondary leading-relaxed mb-4" style="font-size: 0.95rem; line-height: 1.6;">
                    {{ Str::limit($post->body, 220) }}
                </p>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-medium">
                        Read Article &rarr;
                    </a>
                    <span class="badge bg-light text-secondary border rounded-pill px-3 py-2 font-monospace">
                        {{ $post->comments_count }} {{ Str::plural('Comment', $post->comments_count) }}
                    </span>
                </div>
            </article>
        @empty
            <div class="custom-card p-5 text-center shadow-sm">
                <div class="text-muted mb-2 fs-1">🔍</div>
                <h4 class="fw-bold">No Articles Found</h4>
                <p class="text-muted mb-0">Try searching with a different term or return to the main feed.</p>
                @if(request('search'))
                    <a href="{{ route('posts.index') }}" class="btn btn-accent btn-sm mt-3">Reset Search Filter</a>
                @endif
            </div>
        @endforelse

        <!-- Pagination Section -->
        @if(method_exists($posts, 'hasPages') && $posts->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $posts->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>
</div>
@endsection