<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\AdminCommentController;
use App\Http\Controllers\MyPostController;

Route::get('/', function () {
    return redirect()->route('posts.index');
});

Route::get('/posts', [PostController::class, 'index'])
    ->name('posts.index');

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

Route::middleware('auth')->group(function () {

    Route::get('/my-posts', [MyPostController::class, 'index'])
        ->name('posts.my-posts');

    Route::get('/posts/create', [PostController::class, 'create'])
        ->name('posts.create');

    Route::post('/posts', [PostController::class, 'store'])
        ->name('posts.store');

    Route::post('/posts/{post}/comments', [PostController::class, 'storeComment'])
        ->name('comments.store');

    Route::delete('/comments/{comment}', [PostController::class, 'destroyComment'])
        ->name('comments.destroy');

    Route::get('/posts-trash', [PostController::class, 'trash'])
        ->name('posts.trash');

    Route::patch('/posts/{id}/restore', [PostController::class, 'restore'])
        ->name('posts.restore');

    Route::delete('/posts/{id}/force-delete', [PostController::class, 'forceDelete'])
        ->name('posts.forceDelete');

    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])
        ->name('posts.edit');

    Route::put('/posts/{post}', [PostController::class, 'update'])
        ->name('posts.update');

    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
        ->name('posts.destroy');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

});

Route::get('/posts/{post:slug}', [PostController::class, 'show'])
    ->name('posts.show');

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

Route::get('/', [AdminController::class, 'index'])
    ->name('dashboard');

Route::resource('categories', CategoryController::class)
    ->except(['show']);

Route::get('/posts', [AdminPostController::class, 'index'])
    ->name('posts.index');

Route::delete('/posts/{post}', [AdminPostController::class, 'destroy'])
    ->name('posts.destroy');

Route::get('/comments', [AdminCommentController::class, 'index'])
    ->name('comments.index');

Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])
    ->name('comments.destroy');

});
