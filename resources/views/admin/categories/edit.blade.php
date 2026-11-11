@extends('layouts.app')

@section('title', 'Chỉnh sửa danh mục')

@section('content')

    <h2>Chỉnh sửa danh mục</h2>

    <p>
        <a href="{{ route('admin.categories.index') }}">
            Quay lại danh sách danh mục
        </a>
    </p>

    <hr>

    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form
        action="{{ route('admin.categories.update', $category->id) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <p>
            <label>Tên danh mục:</label><br>

            <input
                type="text"
                name="name"
                value="{{ old('name', $category->name) }}"
                required
            >
        </p>

        <p>
            <label>Mô tả:</label><br>

            <textarea
                name="description"
                rows="5"
                placeholder="Nhập mô tả danh mục..."
            >{{ old('description', $category->description) }}</textarea>
        </p>

        <button type="submit">Lưu thay đổi</button>

        <a href="{{ route('admin.categories.index') }}">
            Hủy
        </a>

    </form>

@endsection
