<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Blog cộng đồng')</title>
</head>

<body>

    <h1>Website quản lý bài viết</h1>

    <nav>
        <a href="{{ route('posts.index') }}">Trang chủ</a> |

        @auth
            <a href="{{ route('posts.create') }}">Thêm bài viết</a> |

            <a href="{{ route('posts.my-posts') }}">Bài viết của tôi</a> |

            <a href="{{ route('posts.trash') }}">Thùng rác</a> |

            <span>Xin chào, {{ auth()->user()->name }}</span>

            @if (auth()->user()->role === 'admin')
                <hr>

                <strong>Khu vực quản trị:</strong>

                <a href="{{ route('admin.dashboard') }}">Tổng quan</a> |

                <a href="{{ route('admin.categories.index') }}">Quản lý danh mục</a> |

                <a href="{{ route('admin.posts.index') }}">Quản lý bài viết</a> |

                <a href="{{ route('admin.comments.index') }}">Quản lý bình luận</a>

                <hr>
            @endif

            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit">Đăng xuất</button>
            </form>

        @else
            <a href="{{ route('login') }}">Đăng nhập</a> |
            <a href="{{ route('register') }}">Đăng ký</a>
        @endauth
    </nav>

    <hr>

    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    @if (session('error'))
        <p style="color: red;">
            {{ session('error') }}
        </p>
    @endif

    @yield('content')

</body>

</html>
