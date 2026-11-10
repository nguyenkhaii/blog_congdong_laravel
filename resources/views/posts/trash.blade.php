@extends('layouts.app')

@section('title', 'Thùng rác bài viết')

@section('content')

    <h2>Thùng rác bài viết</h2>

    <p>
        <a href="{{ route('posts.index') }}">Quay lại danh sách bài viết</a>
    </p>

    <hr>

    {{-- Thông báo thành công --}}
    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    {{-- Danh sách bài viết đã xóa --}}
    @forelse ($posts as $post)

        <article>

            <h3>{{ $post->title }}</h3>

            <p>
                Tác giả:
                {{ $post->user->name ?? 'Người dùng đã bị xóa' }}
            </p>

            <p>
                Danh mục:
                {{ $post->category->name ?? 'Chưa phân loại' }}
            </p>

            <p>
                Ngày xóa:
                {{ $post->deleted_at->format('d/m/Y H:i') }}
            </p>

            {{-- Khôi phục bài viết --}}
            <form
                action="{{ route('posts.restore', $post->id) }}"
                method="POST"
                style="display: inline;"
            >
                @csrf
                @method('PATCH')

                <button type="submit">
                    Khôi phục
                </button>
            </form>

            {{-- Xóa vĩnh viễn --}}
            <form
                action="{{ route('posts.forceDelete', $post->id) }}"
                method="POST"
                style="display: inline;"
                onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn bài viết này không?')"
            >
                @csrf
                @method('DELETE')

                <button type="submit">
                    Xóa vĩnh viễn
                </button>
            </form>

        </article>

        <hr>

    @empty

        <p>Thùng rác hiện chưa có bài viết nào.</p>

    @endforelse

    {{-- Phân trang --}}
    {{ $posts->links() }}

@endsection
