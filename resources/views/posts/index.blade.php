@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h1 class="mb-4">Blog Feed</h1>
        @forelse ($posts as $post)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">{{ $post->title }}</h3>
                    <p class="text-muted small">By {{ $post->user->name }} &bull; {{ $post->created_at->diffForHumans() }}</p>
                    <p class="card-text">{{ Str::limit($post->body, 200) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary btn-sm">Read Article</a>
                        <span class="badge bg-secondary">
                            {{ $post->comments_count }} {{ Str::plural('Approved Comment', $post->comments_count) }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No posts found.</div>
        @endforelse
    </div>
</div>
@endsection