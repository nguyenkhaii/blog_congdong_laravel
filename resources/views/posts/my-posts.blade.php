@extends('layouts.app')

@section('title', 'Bài viết của tôi')

@section('content')

    <h2>Bài viết của tôi</h2>

    <p>
        <a href="{{ route('posts.create') }}">+ Đăng bài viết mới</a>
    </p>

    <hr>

    <p>Tổng số bài viết: {{ $posts->total() }}</p>

    @forelse ($posts as $post)

        <article>

            <h3>{{ $post->title }}</h3>

            <p>
                Danh mục:
                {{ $post->category->name ?? 'Chưa phân loại' }}
            </p>

            <p>
                Trạng thái:

                @if ($post->status === 'published')
                    <span style="color: green;">Đã đăng</span>
                @elseif ($post->status === 'draft')
                    <span style="color: orange;">Bản nháp</span>
                @elseif ($post->status === 'scheduled')
                    <span style="color: blue;">Đã lên lịch</span>
                @endif
            </p>

            @if ($post->status === 'scheduled')
                <p>
                    Thời gian đăng:
                    {{ $post->scheduled_at?->format('d/m/Y H:i') }}
                </p>
            @endif

            <p>
                Ngày tạo:
                {{ $post->created_at->format('d/m/Y H:i') }}
            </p>

            <p>
                Tags:

                @forelse ($post->tags as $tag)
                    <span>#{{ $tag->name }}</span>
                @empty
                    <span>Chưa có tag</span>
                @endforelse
            </p>

            <p>
                <a href="{{ route('posts.edit', $post->id) }}">
                    Chỉnh sửa
                </a>

                @if ($post->status === 'published')
                    |
                    <a href="{{ route('posts.show', $post->slug) }}">
                        Xem bài viết
                    </a>
                @endif
            </p>

            <form
                action="{{ route('posts.destroy', $post->id) }}"
                method="POST"
                onsubmit="return confirm('Bạn có chắc muốn chuyển bài viết vào thùng rác?')"
            >
                @csrf
                @method('DELETE')

                <button type="submit">Xóa bài viết</button>
            </form>

        </article>

        <hr>

    @empty

        <p>Bạn chưa có bài viết nào.</p>

    @endforelse

    {{ $posts->links() }}

@endsection
