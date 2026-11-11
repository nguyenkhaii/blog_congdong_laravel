<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Comment;
use App\Mail\CommentPostedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostController extends Controller
{
    // Hiển thị danh sách bài viết công khai
    public function index(Request $request)
    {
        $query = Post::with(['user', 'category', 'tags'])
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag);
            });
        }

        $posts = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();


        return view('posts.index', compact(
            'posts',
            'categories',
            'tags',
        ));
    }

    // Hiển thị chi tiết bài viết
    public function show(Post $post)
    {
        if (
            $post->status !== 'published'
            || !$post->published_at
            || $post->published_at->isFuture()
        ) {
            if (
                !Auth::check()
                || (
                    $post->user_id !== Auth::id()
                    && Auth::user()->role !== 'admin'
                )
            ) {
                abort(404);
            }
        }

        $post->load([
            'user',
            'category',
            'tags',
            'comments.user'
        ]);

        return view('posts.show', compact('post'));
    }

    // Hiển thị form tạo bài viết
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('posts.create', compact('categories', 'tags'));
    }

    // Xử lý tạo bài viết
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:draft,published,scheduled',
            'scheduled_at' => 'nullable|required_if:status,scheduled|date|after:now',
        ]);

        $slug = Str::slug($validated['title']);

        if (Post::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('posts', 'public');
        }

        $tagIds = $validated['tags'] ?? [];

        unset($validated['tags']);

        $status = $validated['status'];

        $validated['slug'] = $slug;
        $validated['user_id'] = Auth::id();

        if ($status === 'published') {
            $validated['published_at'] = now();
            $validated['scheduled_at'] = null;
        } elseif ($status === 'draft') {
            $validated['published_at'] = null;
            $validated['scheduled_at'] = null;
        } else {
            $validated['published_at'] = null;
        }

        $post = Post::create($validated);

        $post->tags()->sync($tagIds);

        Log::info('Thành viên đã tạo bài viết', [
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'title' => $post->title,
            'status' => $post->status,
        ]);

        return redirect()->route('posts.index')
            ->with('success', 'Tạo bài viết thành công!');
    }

    // Hiển thị form chỉnh sửa bài viết
    public function edit(Post $post)
    {
        if (
            $post->user_id !== Auth::id()
            && Auth::user()->role !== 'admin'
        ) {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        $post->load('tags');

        return view('posts.edit', compact(
            'post',
            'categories',
            'tags'
        ));
    }

    // Xử lý cập nhật bài viết
    public function update(Request $request, Post $post)
    {
        if (
            $post->user_id !== Auth::id()
            && Auth::user()->role !== 'admin'
        ) {
            abort(403, 'Bạn không có quyền chỉnh sửa bài viết này.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:draft,published,scheduled',
            'scheduled_at' => 'nullable|required_if:status,scheduled|date|after:now',
        ]);

        $tagIds = $validated['tags'] ?? [];

        unset($validated['tags']);

        if ($post->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);

            if (
                Post::where('slug', $slug)
                    ->where('id', '!=', $post->id)
                    ->exists()
            ) {
                $slug .= '-' . time();
            }

            $validated['slug'] = $slug;
        }

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $validated['image'] = $request->file('image')
                ->store('posts', 'public');
        }

        if ($validated['status'] === 'published') {
            if ($post->status !== 'published' || !$post->published_at) {
                $validated['published_at'] = now();
            }

            $validated['scheduled_at'] = null;
        } elseif ($validated['status'] === 'draft') {
            $validated['published_at'] = null;
            $validated['scheduled_at'] = null;
        } else {
            $validated['published_at'] = null;
        }

        $post->update($validated);

        $post->tags()->sync($tagIds);

        Log::info('Thành viên đã cập nhật bài viết', [
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'title' => $post->title,
            'status' => $post->status,
        ]);

        return redirect()->route('posts.index')
            ->with('success', 'Cập nhật bài viết thành công!');
    }

    // Chuyển bài viết vào thùng rác
    public function destroy(Post $post)
    {
        if (
            $post->user_id !== Auth::id()
            && Auth::user()->role !== 'admin'
        ) {
            abort(403, 'Bạn không có quyền xóa bài viết này.');
        }

        $post->delete();

        Log::info('Thành viên đã chuyển bài viết vào thùng rác', [
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'title' => $post->title,
        ]);

        return redirect()->route('posts.index')
            ->with('success', 'Đã chuyển bài viết vào thùng rác!');
    }

    public function trash()
    {
        $query = Post::onlyTrashed()
            ->with(['category', 'user']);

        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        $posts = $query->latest('deleted_at')->paginate(10);

        return view('posts.trash', compact('posts'));
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);

        if (
            $post->user_id !== Auth::id()
            && Auth::user()->role !== 'admin'
        ) {
            abort(403, 'Bạn không có quyền khôi phục bài viết này.');
        }

        $post->restore();

        Log::info('Thành viên đã khôi phục bài viết', [
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'title' => $post->title,
        ]);

        return redirect()->route('posts.trash')
            ->with('success', 'Khôi phục bài viết thành công!');
    }

    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);

        if (
            $post->user_id !== Auth::id()
            && Auth::user()->role !== 'admin'
        ) {
            abort(403, 'Bạn không có quyền xóa bài viết này.');
        }

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $postId = $post->id;
        $postTitle = $post->title;

        $post->forceDelete();

        Log::warning('Bài viết đã bị xóa vĩnh viễn', [
            'user_id' => Auth::id(),
            'post_id' => $postId,
            'title' => $postTitle,
        ]);

        return redirect()->route('posts.trash')
            ->with('success', 'Đã xóa bài viết vĩnh viễn!');
    }

    public function storeComment(Request $request, Post $post)
    {
        if (
            $post->status !== 'published'
            || !$post->published_at
            || $post->published_at->isFuture()
        ) {
            abort(404);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        Log::info('Thành viên đã bình luận bài viết', [
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'comment_id' => $comment->id,
        ]);

        $post->load('user');

        if ($post->user) {
            Mail::to($post->user->email)
                ->queue(new CommentPostedMail($comment));
        }

        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Bình luận thành công!');
    }

    public function destroyComment(Comment $comment)
    {
        $user = Auth::user();

        if (
            $comment->user_id !== Auth::id()
            && $comment->post->user_id !== Auth::id()
            && $user->role !== 'admin'
        ) {
            abort(403, 'Bạn không có quyền xóa bình luận này.');
        }

        $postSlug = $comment->post->slug;
        $commentId = $comment->id;
        $postId = $comment->post_id;

        $comment->delete();

        Log::info('Bình luận đã bị xóa', [
            'user_id' => Auth::id(),
            'comment_id' => $commentId,
            'post_id' => $postId,
        ]);

        return redirect()->route('posts.show', $postSlug)
            ->with('success', 'Xóa bình luận thành công!');
    }
}
