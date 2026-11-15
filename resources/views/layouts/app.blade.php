<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Blog cộng đồng')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <div class="container">

        <header>
            <h1>Website quản lý bài viết</h1>
            <p>Chia sẻ kiến thức và kết nối cộng đồng</p>
        </header>

        <nav>
            <a href="{{ route('posts.index') }}">Trang chủ</a>

            @auth
                <a href="{{ route('posts.create') }}">Thêm bài viết</a>

                <a href="{{ route('posts.my-posts') }}">Bài viết của tôi</a>

                <a href="{{ route('posts.trash') }}">Thùng rác</a>

                <span class="welcome">
                    Xin chào, {{ auth()->user()->name }}
                </span>

                @if (auth()->user()->role === 'admin')
                    <div class="admin-menu">
                        <strong>Khu vực quản trị:</strong>

                        <a href="{{ route('admin.dashboard') }}">Tổng quan</a>

                        <a href="{{ route('admin.categories.index') }}">Quản lý danh mục</a>

                        <a href="{{ route('admin.posts.index') }}">Quản lý bài viết</a>

                        <a href="{{ route('admin.comments.index') }}">Quản lý bình luận</a>
                    </div>
                @endif

                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit">Đăng xuất</button>
                </form>

            @else
                <a href="{{ route('login') }}">Đăng nhập</a>

                <a href="{{ route('register') }}">Đăng ký</a>
            @endauth
        </nav>

        <main>

            @if (session('success'))
                <p class="success-message">
                    {{ session('success') }}
                </p>
            @endif

            @if (session('error'))
                <p class="error-message">
                    {{ session('error') }}
                </p>
            @endif

            @yield('content')

        </main>

        <footer>
            <p>Blog cộng đồng - Laravel 13</p>
        </footer>

    </div>

</body>

</html>
