<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Log;

class AdminCommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with(['user', 'post'])
            ->latest()
            ->paginate(10);

        return view('admin.comments.index', compact('comments'));
    }

    public function destroy(Comment $comment)
    {
        $commentId = $comment->id;
        $postId = $comment->post_id;

        $comment->delete();

        Log::warning('Admin đã xóa bình luận vi phạm', [
            'user_id' => auth()->id(),
            'comment_id' => $commentId,
            'post_id' => $postId,
        ]);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Đã xóa bình luận thành công!');
    }
}
