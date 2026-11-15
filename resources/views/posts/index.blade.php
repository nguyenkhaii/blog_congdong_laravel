@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')

<h2>Danh sách bài viết</h2>

<form action="{{ route('posts.index') }}" method="GET">

    <input
        type="text"
        name="search"
        placeholder="Tìm kiếm bài viết..."
        value="{{ request('search') }}"
    >

    <select name="category">
        <option value="">Tất cả danh mục</option>

        @foreach ($categories as $category)
            <option
                value="{{ $category->id }}"
                {{ request('category') == $category->id ? 'selected' : '' }}
            >
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <select name="tag">
        <option value="">Tất cả tag</option>

        @foreach ($tags as $tag)
            <option
                value="{{ $tag->id }}"
                {{ request('tag') == $tag->id ? 'selected' : '' }}
            >
                {{ $tag->name }}
            </option>
        @endforeach
    </select>

    <button type="submit">Tìm kiếm</button>

    <a href="{{ route('posts.index') }}">Làm mới</a>

</form>

<hr>

@forelse ($posts as $post)

    <div style="border-bottom: 1px solid #ccc; padding: 15px 0;">

        <h3>
            <a href="{{ route('posts.show', ['post' => $post->slug]) }}">
                {{ $post->title }}
            </a>
        </h3>

        @if ($post->image)
            <p>
                <img
                    src="{{ asset('storage/' . $post->image) }}"
                    alt="{{ $post->title }}"
                    width="200"
                >
            </p>
        @endif

        <p>Tác giả: {{ $post->user->name ?? 'Không xác định' }}</p>

        <p>Danh mục: {{ $post->category->name ?? 'Chưa phân loại' }}</p>

        <p>
            Tag:
            @forelse ($post->tags as $tag)
                {{ $tag->name }}
            @empty
                Chưa có tag
            @endforelse
        </p>

        <p>
            {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 200) }}
        </p>

        <p>
            Ngày đăng: {{ $post->published_at?->format('d/m/Y H:i') }}
        </p>

        <a href="{{ route('posts.show', ['post' => $post->slug]) }}">
            Xem chi tiết
        </a>

    </div>

@empty

    <p>Chưa có bài viết nào.</p>

@endforelse

<br>

{{ $posts->links() }}

@endsection
