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
        <textarea
            name="content"
            rows="8"
            required
        >{{ old('content') }}</textarea>
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

    <p>
        <label>Trạng thái bài viết:</label><br>

        <select name="status" id="status" required>
            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                Bản nháp
            </option>

            <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>
                Công khai
            </option>

            <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>
                Lên lịch
            </option>
        </select>
    </p>

    <p id="schedule-field" style="display: none;">
        <label>Thời gian đăng bài:</label><br>

        <input
            type="datetime-local"
            name="scheduled_at"
            id="scheduled_at"
            value="{{ old('scheduled_at') }}"
        >
    </p>

    <button type="submit">Đăng bài viết</button>

    <a href="{{ route('posts.index') }}">Quay lại</a>

</form>

<script>
    const statusSelect = document.getElementById('status');
    const scheduleField = document.getElementById('schedule-field');
    const scheduledAt = document.getElementById('scheduled_at');

    function checkStatus() {
        if (statusSelect.value === 'scheduled') {
            scheduleField.style.display = 'block';
            scheduledAt.required = true;
        } else {
            scheduleField.style.display = 'none';
            scheduledAt.required = false;
        }
    }

    statusSelect.addEventListener('change', checkStatus);

    checkStatus();
</script>

@endsection
