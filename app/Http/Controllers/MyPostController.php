<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class MyPostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['category', 'tags'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('posts.my-posts', compact('posts'));
    }
}
