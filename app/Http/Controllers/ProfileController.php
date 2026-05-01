<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $likedPosts = Post::whereHas('likes', function($q) use ($user) {
            $q->where('user_id', $user->id)->where('like', true);
        })->get();

        return view('profile', compact('user', 'likedPosts'));
    }
}