@extends('layouts.app')

@section('title', 'Đăng ký tài khoản')

@section('content')

    <h2>Đăng ký tài khoản</h2>

    {{-- Thông báo lỗi --}}
    @if ($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('register.store') }}" method="POST">

        @csrf

        <p>
            <label>Họ tên:</label><br>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
            >
        </p>

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

        <p>
            <label>Nhập lại mật khẩu:</label><br>
            <input
                type="password"
                name="password_confirmation"
                required
            >
        </p>

        <button type="submit">Đăng ký</button>

    </form>

    <p>
        Đã có tài khoản?
        <a href="{{ route('login') }}">Đăng nhập</a>
    </p>

@endsection
