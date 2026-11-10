<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class MyPostController extends Controller
{
    // Hiển thị danh sách bài viết của thành viên
    public function index()
    {
        $posts = Post::with(['category', 'tags'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('posts.my-posts', compact('posts'));
    }
}
