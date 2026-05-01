<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['likes', 'dislikes', 'comments.user'])->latest()->paginate(12);
        $topPosts = Post::withCount('likes')->orderBy('likes_count', 'desc')->take(3)->get();
        return view('posts', compact('posts', 'topPosts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        }

        Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $imageName
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created successfully');
    }

    public function ajaxLike(Request $request)
    {
        $response = auth()->user()->toggleLikeDislike($request->id, $request->like);
        $post = Post::find($request->id);

        return response()->json([
            'success' => true,
            'status' => $response,
            'likes' => $post->likes()->count(),
            'dislikes' => $post->dislikes()->count(),
        ]);
    }

    public function topPosts()
    {
        $topPosts = Post::withCount('likes')->orderBy('likes_count', 'desc')->take(3)->get();
        return response()->json($topPosts);
    }
}