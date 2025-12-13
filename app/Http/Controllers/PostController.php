<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller {
    public function index(){
        $posts = Post::all();
        return view('posts', compact('posts'));
    }

    public function ajaxLike(Request $request){
        $response = auth()->user()->toggleLikeDislike($request->id, $request->like);
        return response()->json(['success' => $response]);
    }
}

