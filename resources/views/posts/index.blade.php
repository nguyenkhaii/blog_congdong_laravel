@extends('layouts.app')

@section('title', 'Quản lý bài viết')

@section('content')

    <h2>Quản lý bài viết</h2>

    <p>
        <a href="{{ route('admin.dashboard') }}">
            Quay lại trang quản trị
        </a>
    </p>

    <hr>

    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    <h3>Danh sách tất cả bài viết</h3>

    <p>Tổng số bài viết: {{ $posts->total() }}</p>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">

        <thead>
            <tr>
                <th>STT</th>
                <th>Tiêu đề</th>
                <th>Tác giả</th>
                <th>Danh mục</th>
                <th>Trạng thái</th>
                <th>Số bình luận</th>
                <th>Ngày đăng</th>
                <th>Thao tác</th>
            </tr>
        </thead>

        <tbody>

            @forelse ($posts as $index => $post)

                <tr>
                    <td>{{ $posts->firstItem() + $index }}</td>

                    <td>{{ $post->title }}</td>

                    <td>
                        {{ $post->user->name ?? 'Người dùng đã bị xóa' }}
                    </td>

                    <td>
                        {{ $post->category->name ?? 'Chưa phân loại' }}
                    </td>

                    <td>
                        @if ($post->status === 'published')
                            Đã đăng
                        @elseif ($post->status === 'draft')
                            Bản nháp
                        @elseif ($post->status === 'scheduled')
                            Đã lên lịch
                        @else
                            {{ $post->status }}
                        @endif
                    </td>

                    <td>{{ $post->comments_count }}</td>

                    <td>
                        {{ $post->published_at?->format('d/m/Y H:i') ?? 'Chưa đăng' }}
                    </td>

                    <td>
                        @if (
                            $post->status === 'published'
                            && $post->published_at
                            && !$post->published_at->isFuture()
                        )
                            <a href="{{ route('posts.show', $post->slug) }}">
                                Xem
                            </a>
                        @else
                            <span>Chưa công khai</span>
                        @endif

                        <form
                            action="{{ route('admin.posts.destroy', $post->id) }}"
                            method="POST"
                            style="display: inline;"
                            onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này không?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit">Xóa</button>
                        </form>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="8" align="center">
                        Chưa có bài viết nào.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

    <br>

    {{ $posts->links() }}

@endsection
