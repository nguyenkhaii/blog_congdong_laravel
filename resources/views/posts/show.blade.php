@extends('layouts.app')

@section('title', $post->title)

@section('content')

    <article>

        {{-- Tiêu đề --}}
        <h2>{{ $post->title }}</h2>

        {{-- Thông tin bài viết --}}
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
            {{ $post->published_at?->format('d/m/Y H:i') ?? $post->created_at->format('d/m/Y H:i') }}
        </p>

        <hr>

        {{-- Ảnh bài viết --}}
        @if ($post->image)
            <div>
                <img
                    src="{{ asset('storage/' . $post->image) }}"
                    alt="{{ $post->title }}"
                    style="max-width: 100%; height: auto;"
                >
            </div>
        @endif

        {{-- Nội dung --}}
        <h3>Nội dung bài viết</h3>

        <div>
            {!! nl2br(e($post->content)) !!}
        </div>

        <hr>

        {{-- Tags --}}
        <h3>Tags</h3>

        @forelse ($post->tags as $tag)
            <span>#{{ $tag->name }}</span>
        @empty
            <p>Bài viết chưa có Tags.</p>
        @endforelse

        <hr>

        {{-- Bình luận --}}
        <h3>Bình luận ({{ $post->comments->count() }})</h3>

        {{-- Hiển thị thông báo lỗi --}}
        @if ($errors->any())
            <div style="color: red;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Danh sách bình luận --}}
        @forelse ($post->comments as $comment)

            <div style="margin-bottom: 20px;">

                <strong>
                    {{ $comment->user->name ?? 'Người dùng đã bị xóa' }}
                </strong>

                <small>
                    {{ $comment->created_at->format('d/m/Y H:i') }}
                </small>

                <p>{{ $comment->content }}</p>

                {{-- Kiểm tra quyền xóa bình luận --}}
                @auth

                    @if (
                        $comment->user_id === auth()->id()
                        || $post->user_id === auth()->id()
                        || auth()->user()->role === 'admin'
                    )

                        <form
                            action="{{ route('comments.destroy', $comment->id) }}"
                            method="POST"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                onclick="return confirm('Bạn có chắc muốn xóa bình luận này không?')"
                            >
                                Xóa bình luận
                            </button>
                        </form>

                    @endif

                @endauth

            </div>

            <hr>

        @empty

            <p>Chưa có bình luận nào.</p>

        @endforelse

        {{-- Form thêm bình luận --}}
        <h3>Viết bình luận</h3>

        @auth

            <form
                action="{{ route('comments.store', $post->id) }}"
                method="POST"
            >

                @csrf

                <p>
                    <textarea
                        name="content"
                        rows="4"
                        placeholder="Nhập nội dung bình luận..."
                        required
                    >{{ old('content') }}</textarea>
                </p>

                <button type="submit">
                    Gửi bình luận
                </button>

            </form>

        @else

            <p>
                Vui lòng
                <a href="{{ route('login') }}">đăng nhập</a>
                để viết bình luận.
            </p>

        @endauth

    </article>

    <hr>

    <p>
        <a href="{{ route('posts.index') }}">Quay lại trang chủ</a>
    </p>

@endsection
