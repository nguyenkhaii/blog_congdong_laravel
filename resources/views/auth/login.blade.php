@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')

    <h2>Đăng nhập</h2>

    {{-- Hiển thị thông báo lỗi --}}
    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('login.store') }}" method="POST">

        @csrf

        <p>
            <label>Email:</label><br>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
            >
        </p>

        <p>
            <label>Mật khẩu:</label><br>
            <input
                type="password"
                name="password"
                required
            >
        </p>

        <button type="submit">Đăng nhập</button>

    </form>

    <p>
        Chưa có tài khoản?
        <a href="{{ route('register') }}">Đăng ký ngay</a>
    </p>

@endsection
