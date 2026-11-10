<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\AdminCommentController;
use App\Http\Controllers\MyPostController;

// Trang chủ
Route::get('/', function () {
    return redirect()->route('posts.index');
});

// Các đường dẫn công khai
Route::get('/posts', [PostController::class, 'index'])
    ->name('posts.index');

// Đăng ký và đăng nhập
Route::middleware('guest')->group(function () {

    Route::get('/register', [AuthController::class, 'register'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'store'])
        ->name('register.store');

    Route::get('/login', [AuthController::class, 'login'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'loginStore'])
        ->name('login.store');

});

// Các chức năng yêu cầu đăng nhập
Route::middleware('auth')->group(function () {

    // Bài viết của tôi
    Route::get('/my-posts', [MyPostController::class, 'index'])
        ->name('posts.my-posts');

    // Tạo bài viết
    Route::get('/posts/create', [PostController::class, 'create'])
        ->name('posts.create');

    Route::post('/posts', [PostController::class, 'store'])
        ->name('posts.store');

    // Thêm bình luận
    Route::post('/posts/{post}/comments', [PostController::class, 'storeComment'])
        ->name('comments.store');

    // Xóa bình luận
    Route::delete('/comments/{comment}', [PostController::class, 'destroyComment'])
        ->name('comments.destroy');

    // Thùng rác
    Route::get('/posts-trash', [PostController::class, 'trash'])
        ->name('posts.trash');

    // Khôi phục bài viết
    Route::patch('/posts/{id}/restore', [PostController::class, 'restore'])
        ->name('posts.restore');

    // Xóa vĩnh viễn
    Route::delete('/posts/{id}/force-delete', [PostController::class, 'forceDelete'])
        ->name('posts.forceDelete');

    // Chỉnh sửa bài viết
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])
        ->name('posts.edit');

    Route::put('/posts/{post}', [PostController::class, 'update'])
        ->name('posts.update');

    // Chuyển bài viết vào thùng rác
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
        ->name('posts.destroy');

    // Đăng xuất
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

});

// Chi tiết bài viết bằng Slug
Route::get('/posts/{post:slug}', [PostController::class, 'show'])
    ->name('posts.show');

// Các chức năng chỉ dành cho Admin
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Trang tổng quan
        Route::get('/', [AdminController::class, 'index'])
            ->name('dashboard');

        // Quản lý danh mục
        Route::resource('categories', CategoryController::class)
            ->except(['show']);

        // Quản lý bài viết
        Route::get('/posts', [AdminPostController::class, 'index'])
            ->name('posts.index');

        Route::delete('/posts/{post}', [AdminPostController::class, 'destroy'])
            ->name('posts.destroy');

        // Quản lý bình luận
        Route::get('/comments', [AdminCommentController::class, 'index'])
            ->name('comments.index');

        Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])
            ->name('comments.destroy');

    });
