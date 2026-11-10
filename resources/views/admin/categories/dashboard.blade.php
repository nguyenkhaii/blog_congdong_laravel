@extends('layouts.app')

@section('title', 'Quản trị hệ thống')

@section('content')

    <h2>Trang quản trị Admin</h2>

    <p>Chào mừng bạn đến với hệ thống quản trị Blog cộng đồng.</p>

    <hr>

    <h3>Thống kê tổng quan</h3>

    <div style="display: flex; gap: 20px; flex-wrap: wrap;">

        <div style="border: 1px solid #ccc; padding: 20px; min-width: 180px;">
            <h3>Tổng thành viên</h3>
            <h2>{{ $totalUsers }}</h2>
        </div>

        <div style="border: 1px solid #ccc; padding: 20px; min-width: 180px;">
            <h3>Bài viết đã đăng</h3>
            <h2>{{ $totalPosts }}</h2>
        </div>

        <div style="border: 1px solid #ccc; padding: 20px; min-width: 180px;">
            <h3>Tổng bình luận</h3>
            <h2>{{ $totalComments }}</h2>
        </div>

    </div>

    <hr>

    <h3>Top 5 bài viết có nhiều bình luận nhất</h3>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">

        <thead>
            <tr>
                <th>STT</th>
                <th>Tiêu đề bài viết</th>
                <th>Tác giả</th>
                <th>Số bình luận</th>
            </tr>
        </thead>

        <tbody>

            @forelse ($topPosts as $index => $post)

                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>{{ $post->title }}</td>

                    <td>
                        {{ $post->user->name ?? 'Người dùng đã bị xóa' }}
                    </td>

                    <td>{{ $post->comments_count }}</td>
                </tr>

            @empty

                <tr>
                    <td colspan="4" align="center">
                        Chưa có bài viết nào.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

    <hr>

    <p>
        <a href="{{ route('posts.index') }}">Quay lại trang chủ</a>
    </p>

@endsection
