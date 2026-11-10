@extends('layouts.app')

@section('title', 'Thêm danh mục')

@section('content')

    <h2>Thêm danh mục mới</h2>

    <p>
        <a href="{{ route('admin.categories.index') }}">
            Quay lại danh sách danh mục
        </a>
    </p>

    <hr>

    {{-- Hiển thị lỗi --}}
    @if ($errors->any())
        <div style="color: red;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Form thêm danh mục --}}
    <form action="{{ route('admin.categories.store') }}" method="POST">

        @csrf

        <p>
            <label>Tên danh mục:</label><br>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Nhập tên danh mục..."
                required
            >
        </p>

        <p>
            <label>Mô tả:</label><br>

            <textarea
                name="description"
                rows="5"
                placeholder="Nhập mô tả danh mục..."
            >{{ old('description') }}</textarea>
        </p>

        <button type="submit">Thêm danh mục</button>

        <a href="{{ route('admin.categories.index') }}">
            Hủy
        </a>

    </form>

@endsection
