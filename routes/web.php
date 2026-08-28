<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

Route::get('/', [PostController::class, 'index'])->name('posts.index');
// Route uses {slug} parameter instead of {id}
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/posts-create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts-store', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{postId}/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::get('/admin/comments', [CommentController::class, 'pending'])->name('admin.comments.index');
    Route::patch('/admin/comments/{commentId}/approve', [CommentController::class, 'approve'])->name('admin.comments.approve');
});