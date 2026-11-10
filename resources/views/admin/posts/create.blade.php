@extends('layouts.app')

@section('title', 'Đăng bài viết')

@section('content')

    <h2>Đăng bài viết mới</h2>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form
        action="{{ route('posts.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <p>
            <label>Tiêu đề bài viết:</label><br>
            <input
                type="text"
                name="title"
                value="{{ old('title') }}"
                required
            >
        </p>

        <p>
            <label>Nội dung bài viết:</label><br>
            <textarea name="content" rows="8" required>{{ old('content') }}</textarea>
        </p>

        <p>
            <label>Danh mục:</label><br>
            <select name="category_id">
                <option value="">-- Chọn danh mục --</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </p>

        <p>
            <label>Tags (có thể chọn nhiều):</label><br>

            @foreach ($tags as $tag)
                <label style="display: inline-block; margin: 5px;">
                    <input
                        type="checkbox"
                        name="tags[]"
                        value="{{ $tag->id }}"
                        {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                    >
                    {{ $tag->name }}
                </label>
            @endforeach
        </p>

        <p>
            <label>Ảnh bài viết:</label><br>
            <input
                type="file"
                name="image"
                accept="image/jpeg,image/png,image/webp"
            >
        </p>

        <hr>

        <h3>Trạng thái bài viết</h3>

        <p>
            <label>
                <input
                    type="radio"
                    name="status"
                    value="draft"
                    {{ old('status', 'published') === 'draft' ? 'checked' : '' }}
                >
                Lưu bản nháp
            </label>
        </p>

        <p>
            <label>
                <input
                    type="radio"
                    name="status"
                    value="published"
                    {{ old('status', 'published') === 'published' ? 'checked' : '' }}
                >
                Đăng ngay
            </label>
        </p>

        <p>
            <label>
                <input
                    type="radio"
                    name="status"
                    value="scheduled"
                    {{ old('status') === 'scheduled' ? 'checked' : '' }}
                >
                Lên lịch đăng bài
            </label>
        </p>

        <p>
            <label>Thời gian đăng bài:</label><br>
            <input
                type="datetime-local"
                name="scheduled_at"
                value="{{ old('scheduled_at') }}"
            >
        </p>

        <button type="submit">Lưu bài viết</button>

        <a href="{{ route('posts.index') }}">Quay lại</a>

    </form>

@endsection
