@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <h2 class="mb-4">Pending Comments Approval</h2>
        @forelse ($pendingComments as $comment)
            <div class="card mb-3 border-warning shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">
                            By <strong>{{ $comment->user->name }}</strong> on 
                            <a href="{{ route('posts.show', $comment->post) }}">{{ $comment->post->title }}</a>
                        </h6>
                        <p class="mb-0 text-dark">{{ $comment->body }}</p>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="alert alert-success">No pending comments left for review.</div>
        @endforelse
    </div>
</div>
@endsection