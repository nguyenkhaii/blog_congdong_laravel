@extends('layouts.app')

@section('title', 'Quản lý bình luận')

@section('content')

    <h2>Quản lý bình luận</h2>

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

    <h3>Danh sách tất cả bình luận</h3>

    <p>Tổng số bình luận: {{ $comments->total() }}</p>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">

        <thead>
            <tr>
                <th>STT</th>
                <th>Nội dung</th>
                <th>Người bình luận</th>
                <th>Bài viết</th>
                <th>Ngày bình luận</th>
                <th>Thao tác</th>
            </tr>
        </thead>

        <tbody>

            @forelse ($comments as $index => $comment)

                <tr>
                    <td>{{ $comments->firstItem() + $index }}</td>

                    <td>{{ $comment->content }}</td>

                    <td>
                        {{ $comment->user->name ?? 'Người dùng đã bị xóa' }}
                    </td>

                    <td>
                        {{ $comment->post->title ?? 'Bài viết đã bị xóa' }}
                    </td>

                    <td>
                        {{ $comment->created_at->format('d/m/Y H:i') }}
                    </td>

                    <td>
                        <form
                            action="{{ route('admin.comments.destroy', $comment->id) }}"
                            method="POST"
                            onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này không?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit">Xóa bình luận</button>
                        </form>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="6" align="center">
                        Chưa có bình luận nào.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

    <br>

    {{ $comments->links() }}

@endsection
