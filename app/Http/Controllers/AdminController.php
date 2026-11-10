<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class AdminController extends Controller
{
    // Trang tổng quan Admin
    public function index()
    {
        // Tổng số thành viên
        $totalUsers = User::where('role', 'member')->count();

        // Tổng số bài viết đã đăng
        $totalPosts = Post::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->count();

        // Tổng số bình luận
        $totalComments = Comment::count();

        // Top 5 bài viết có nhiều bình luận nhất
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
