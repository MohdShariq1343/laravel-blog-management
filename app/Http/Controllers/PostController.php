<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller {
    public function index() {
        $posts = Post::with('user')
            ->withCount(['comments' => function ($q) {
                $q->where('is_approved', true);
            }])
            ->latest()
            ->get();

        return view('posts.index', compact('posts'));
    }

    public function create() {
        return view('posts.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        Post::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('posts.index')->with('success', 'Post published successfully!');
    }

    public function show(Post $post) {
        $post->load(['user', 'approvedComments.user']);
        return view('posts.show', compact('post'));
    }
}