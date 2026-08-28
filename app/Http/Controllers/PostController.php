<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller {

   public function index(Request $request)
{
    $search = $request->input('search');

    $posts = DB::table('posts')
        ->leftJoin('users', 'posts.user_id', '=', 'users.id')
        ->select('posts.*', 'users.name as author_name') // Alias user's name as author_name
        ->selectSub(function ($query) {
            $query->from('comments')
                ->whereColumn('comments.post_id', 'posts.id')
                ->where('comments.is_approved', true)
                ->selectRaw('count(*)');
        }, 'comments_count')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('posts.title', 'like', "%{$search}%")
                  ->orWhere('posts.body', 'like', "%{$search}%");
            });
        })
        ->latest('posts.created_at')
        ->paginate(6);

    return view('posts.index', compact('posts'));
}

    public function create() {
        return view('posts.create');
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255', // Title does NOT need to be unique
            'body'  => 'required|string',
        ]);

        // 1. Generate Base Slug
        $baseSlug = Str::slug($request->title);
        $slug = $baseSlug;
        $count = 1;

        // 2. Check for duplicate slugs and append incremental counter
        while (DB::table('posts')->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        // 3. Insert Post using Query Builder
        DB::table('posts')->insert([
            'user_id'    => Auth::id(),
            'title'      => $request->title,
            'slug'       => $slug,
            'body'       => $request->body,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('posts.index')->with('success', 'Post published successfully!');
    }

    public function show($slug) {
        // Fetch post using SLUG instead of ID
        $post = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select(
                'posts.id',
                'posts.title',
                'posts.slug',
                'posts.body',
                'posts.created_at',
                'users.name as author_name'
            )
            ->where('posts.slug', $slug)
            ->first();

        if (!$post) {
            abort(404, 'Blog post not found.');
        }

        // Fetch top-level approved comments (parent_id IS NULL)
        $comments = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->select(
                'comments.id',
                'comments.body',
                'comments.created_at',
                'users.name as user_name'
            )
            ->where('comments.post_id', $post->id)
            ->whereNull('comments.parent_id')
            ->where('comments.is_approved', 1)
            ->orderBy('comments.created_at', 'desc')
            ->get();

        // Attach approved replies to each parent comment
        foreach ($comments as $comment) {
            $comment->replies = DB::table('comments')
                ->join('users', 'comments.user_id', '=', 'users.id')
                ->select(
                    'comments.id',
                    'comments.body',
                    'comments.created_at',
                    'users.name as user_name'
                )
                ->where('comments.parent_id', $comment->id)
                ->where('comments.is_approved', 1)
                ->orderBy('comments.created_at', 'asc')
                ->get();
        }

        $post->comments = $comments;

        return view('posts.show', compact('post'));
    }
}