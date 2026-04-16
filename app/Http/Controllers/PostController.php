<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::all();

        //  Top liked posts
        $topPosts = Post::withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->take(3)
            ->get();

        return view('posts', compact('posts', 'topPosts'));
    }

    public function ajaxLike(Request $request)
    {
        $response = auth()->user()->toggleLikeDislike($request->id, $request->like);

        $post = Post::find($request->id);

        return response()->json([
            'success' => $response,
            'likes' => $post->likes()->count(),
            'dislikes' => $post->dislikes()->count(),
        ]);
    }

    public function topPosts()
    {
        $topPosts = \App\Models\Post::withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->take(3)
            ->get();

        return response()->json($topPosts);
    }
}
