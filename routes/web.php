<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

Route::get('/', fn() => view('welcome'));

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('posts/store', [PostController::class, 'store'])->name('posts.store');
    Route::post('posts/ajax-like-dislike', [PostController::class, 'ajaxLike'])->name('posts.ajax.like.dislike');
    Route::get('top-posts', [PostController::class, 'topPosts']);
    
    Route::post('comments', [CommentController::class, 'store'])->name('comments.store');
    
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('notifications', [ProfileController::class, 'notifications'])->name('notifications');
});