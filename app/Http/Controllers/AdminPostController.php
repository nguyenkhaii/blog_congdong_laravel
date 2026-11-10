<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Log;

class AdminPostController extends Controller
{
    // Hiển thị tất cả bài viết
    public function index()
    {
        $posts = Post::with(['user', 'category'])
            ->withCount('comments')
            ->latest()
            ->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    // Xóa bài viết vi phạm
    public function destroy(Post $post)
    {
        $postId = $post->id;
        $postTitle = $post->title;

        $post->delete();

        Log::warning('Admin đã xóa bài viết vi phạm', [
            'user_id' => auth()->id(),
            'post_id' => $postId,
            'title' => $postTitle,
        ]);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Đã xóa bài viết thành công!');
    }
}
