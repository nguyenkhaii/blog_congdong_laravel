@extends('layouts.app')

@section('title', 'Quản lý danh mục')

@section('content')

    <h2>Quản lý danh mục</h2>

    <p>
        <a href="{{ route('admin.dashboard') }}">Quay lại trang quản trị</a>
    </p>

    <p>
        <a href="{{ route('admin.categories.create') }}">
            + Thêm danh mục mới
        </a>
    </p>

    <hr>

    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    <h3>Danh sách danh mục</h3>

    <p>Tổng số danh mục: {{ $categories->total() }}</p>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">

        <thead>
            <tr>
                <th>STT</th>
                <th>Tên danh mục</th>
                <th>Slug</th>
                <th>Mô tả</th>
                <th>Số bài viết</th>
                <th>Thao tác</th>
            </tr>
        </thead>

        <tbody>

            @forelse ($categories as $index => $category)

                <tr>
                    <td>{{ $categories->firstItem() + $index }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>{{ $category->description ?? 'Không có mô tả' }}</td>
                    <td>{{ $category->posts_count }}</td>

                    <td>
                        <a href="{{ route('admin.categories.edit', $category->id) }}">
                            Sửa
                        </a>

                        <form
                            action="{{ route('admin.categories.destroy', $category->id) }}"
                            method="POST"
                            style="display: inline;"
                            onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này không?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit">Xóa</button>
                        </form>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="6" align="center">
                        Chưa có danh mục nào.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

    <br>

    {{ $categories->links() }}

@endsection
