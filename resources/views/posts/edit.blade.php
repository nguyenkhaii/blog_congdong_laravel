@extends('layouts.app')

@section('title', 'Chỉnh sửa bài viết')

@section('content')

    <h2>Chỉnh sửa bài viết</h2>

    {{-- Hiển thị lỗi --}}
    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form
        action="{{ route('posts.update', $post->id) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')

        {{-- Tiêu đề --}}
        <p>
            <label>Tiêu đề bài viết:</label><br>

            <input
                type="text"
                name="title"
                value="{{ old('title', $post->title) }}"
                required
            >
        </p>

        {{-- Nội dung --}}
        <p>
            <label>Nội dung bài viết:</label><br>

            <textarea
                name="content"
                rows="8"
                required
            >{{ old('content', $post->content) }}</textarea>
        </p>

        {{-- Danh mục --}}
        <p>
            <label>Danh mục:</label><br>

            <select name="category_id">

                <option value="">-- Chọn danh mục --</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>
                @endforeach

            </select>
        </p>

        {{-- Tags --}}
        <p>
            <label>Tags (có thể chọn nhiều):</label><br>

            @php
                $selectedTags = old('tags', $post->tags->pluck('id')->toArray());
            @endphp

            @foreach ($tags as $tag)

                <label style="display: inline-block; margin: 5px;">

                    <input
                        type="checkbox"
                        name="tags[]"
                        value="{{ $tag->id }}"
                        {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}
                    >

                    {{ $tag->name }}

                </label>

            @endforeach
        </p>

        {{-- Ảnh hiện tại --}}
        <p>
            <label>Ảnh hiện tại:</label><br>

            @if ($post->image)

                <img
                    src="{{ asset('storage/' . $post->image) }}"
                    alt="{{ $post->title }}"
                    style="max-width: 250px; height: auto;"
                >

            @else

                <span>Bài viết chưa có ảnh.</span>

            @endif

        </p>

        {{-- Chọn ảnh mới --}}
        <p>
            <label>Chọn ảnh mới (không bắt buộc):</label><br>

            <input
                type="file"
                name="image"
                accept="image/jpeg,image/png,image/webp"
            >
        </p>

        <button type="submit">Lưu thay đổi</button>

        <a href="{{ route('posts.index') }}">Quay lại</a>

    </form>

@endsection
