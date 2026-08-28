<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller {

    public function store(Request $request, $postId) {
        $request->validate([
            'body'      => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $postExists = DB::table('posts')->where('id', $postId)->exists();
        if (!$postExists) {
            abort(404, 'Post not found.');
        }

        // AUTO-APPROVE if user is admin, else require approval
        $isApproved = Auth::user()->is_admin ? 1 : 0;

        DB::table('comments')->insert([
            'post_id'     => $postId,
            'user_id'     => Auth::id(),
            'parent_id'   => $request->parent_id ?? null,
            'body'        => $request->body,
            'is_approved' => $isApproved,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        $message = $isApproved 
            ? 'Comment published!' 
            : 'Comment submitted! It will appear once approved by an admin.';

        return back()->with('success', $message);
    }

    public function pending() {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $pendingComments = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->join('posts', 'comments.post_id', '=', 'posts.id')
            ->select(
                'comments.id',
                'comments.body',
                'comments.created_at',
                'users.name as user_name',
                'posts.slug as post_slug',
                'posts.title as post_title'
            )
            ->where('comments.is_approved', 0)
            ->orderBy('comments.created_at', 'desc')
            ->get();

        return view('admin.comments', compact('pendingComments'));
    }

    public function approve($commentId) {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        DB::table('comments')->where('id', $commentId)->update([
            'is_approved' => 1,
            'updated_at'  => now(),
        ]);

        return back()->with('success', 'Comment approved!');
    }
}