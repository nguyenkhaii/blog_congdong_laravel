<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'member')->count();

        $totalPosts = Post::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->count();

        $totalComments = Comment::count();

        $topPosts = Post::withCount('comments')
            ->with('user')
            ->where('status', 'published')
            ->orderByDesc('comments_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPosts',
            'totalComments',
            'topPosts'
        ));
    }
}
