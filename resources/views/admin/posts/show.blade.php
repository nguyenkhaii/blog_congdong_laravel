@extends('layouts.app')

@section('title', $post->title)

@section('content')

    <h2>{{ $post->title }}</h2>

    <p>
        Tác giả:
        {{ $post->user->name ?? 'Người dùng đã bị xóa' }}
    </p>

    <p>
        Danh mục:
        {{ $post->category->name ?? 'Chưa phân loại' }}
    </p>

    <p>
        Ngày đăng:
        {{ $post->published_at?->format('d/m/Y H:i') }}
    </p>

    @if ($post->image)
        <p>
            <img
                src="{{ asset('storage/' . $post->image) }}"
                alt="{{ $post->title }}"
                width="400"
            >
        </p>
    @endif

    <hr>

    <div>
        {!! nl2br(e($post->content)) !!}
    </div>

    <hr>

    <h3>Tags</h3>

    @forelse ($post->tags as $tag)
        <span>{{ $tag->name }}</span>
    @empty
        <p>Bài viết chưa có tag.</p>
    @endforelse

    <hr>

    <h3>Bình luận</h3>

    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    @forelse ($post->comments as $comment)

        <div>
            <p>
                <strong>
                    {{ $comment->user->name ?? 'Người dùng đã bị xóa' }}
                </strong>
            </p>

            <p>{{ $comment->content }}</p>

            <p>
                {{ $comment->created_at->format('d/m/Y H:i') }}
            </p>

            @auth
                @if (
                    $comment->user_id === auth()->id()
                    || $post->user_id === auth()->id()
                    || auth()->user()->role === 'admin'
                )
                    <form
                        action="{{ route('comments.destroy', $comment->id) }}"
                        method="POST"
                        onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này không?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit">Xóa bình luận</button>
                    </form>
                @endif
            @endauth

        </div>

        <hr>

    @empty
        <p>Chưa có bình luận nào.</p>
    @endforelse

    @auth
        <h3>Viết bình luận</h3>

        <form
            action="{{ route('comments.store', $post->id) }}"
            method="POST"
        >
            @csrf

            <div>
                <textarea
                    name="content"
                    rows="4"
                    cols="50"
                    placeholder="Nhập nội dung bình luận..."
                    required
                >{{ old('content') }}</textarea>

                @error('content')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>

            <br>

            <button type="submit">Gửi bình luận</button>
        </form>
    @else
        <p>
            <a href="{{ route('login') }}">Đăng nhập</a>
            để viết bình luận.
        </p>
    @endauth

    <hr>

    <a href="{{ route('posts.index') }}">Quay lại danh sách bài viết</a>

@endsection
