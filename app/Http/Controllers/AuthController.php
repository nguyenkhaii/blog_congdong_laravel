<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Hiển thị trang đăng ký
    public function register()
    {
        return view('auth.register');
    }

    // Xử lý đăng ký
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'member',
        ]);

        Log::info('Register success', [
            'email' => $validated['email'],
            'time' => now()->toDateTimeString(),
        ]);

        return redirect()->route('login');
    }

    // Hiển thị trang đăng nhập
    public function login()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function loginStore(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            Log::info('Login success', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'time' => now()->toDateTimeString(),
            ]);

            return redirect()->route('posts.index');
        }

        Log::warning('Login failed', [
            'email' => $credentials['email'],
            'time' => now()->toDateTimeString(),
        ]);

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    // Xử lý đăng xuất
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Log::info('Logout', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'time' => now()->toDateTimeString(),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
