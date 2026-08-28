<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller {
    public function store(Request $request, Post $post) {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'body' => $request->body,
            'is_approved' => false,
        ]);

        return back()->with('success', 'Comment submitted! It will appear once approved by an admin.');
    }

    public function pending() {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $pendingComments = Comment::with(['user', 'post'])->where('is_approved', false)->latest()->get();
        return view('admin.comments', compact('pendingComments'));
    }

    public function approve(Comment $comment) {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $comment->update(['is_approved' => true]);
        return back()->with('success', 'Comment approved!');
    }
}