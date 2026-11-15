# Blog cộng đồng

Website Blog cộng đồng được xây dựng bằng Laravel 13, phục vụ việc đăng tải, quản lý bài viết và bình luận.

## 1. Công nghệ sử dụng

- PHP
- Laravel 13
- MySQL
- HTML
- CSS
- JavaScript
- Blade Template Engine

## 2. Các chức năng đã thực hiện

### Trang công khai

- Hiển thị danh sách bài viết.
- Xem chi tiết bài viết.
- Tìm kiếm bài viết.
- Lọc bài viết theo danh mục và thẻ.
- Phân trang bài viết.
- Hiển thị thông tin tác giả và bình luận.

### Thành viên

- Đăng ký tài khoản.
- Đăng nhập và đăng xuất.
- Tạo bài viết.
- Chỉnh sửa bài viết của bản thân.
- Quản lý bài viết cá nhân.
- Xóa bài viết vào thùng rác.
- Khôi phục và xóa bài viết.

### Bình luận

- Thành viên có thể bình luận bài viết.
- Hiển thị nội dung và người bình luận.
- Xóa bình luận theo quyền được cấp.
- Tạo email thông báo khi có bình luận mới.

### Quản trị viên

- Trang tổng quan quản trị.
- Quản lý danh mục.
- Quản lý bài viết.
- Quản lý bình luận.
- Thống kê dữ liệu cơ bản.

## 3. Giao diện

Website sử dụng Blade và CSS tự viết, với giao diện đơn giản, dễ sử dụng.

## 4. Hướng dẫn cài đặt

### Bước 1: Clone dự án

```bash
git clone https://github.com/nguyenkhaii/blog_congdong_laravel.git
```

### Bước 2: Truy cập thư mục

```bash
cd blog_congdong_laravel
```

### Bước 3: Cài đặt thư viện

```bash
composer install
```

### Bước 4: Tạo file môi trường

```bash
copy .env.example .env
```

### Bước 5: Tạo khóa ứng dụng

```bash
php artisan key:generate
```

### Bước 6: Cấu hình database

Tạo database `blog_congdong_laravel` trong phpMyAdmin và cấu hình thông tin kết nối trong file `.env`.

### Bước 7: Chạy Migration và Seeder

```bash
php artisan migrate --seed
```

### Bước 8: Khởi động website

```bash
php artisan serve
```

Truy cập:

http://127.0.0.1:8000

## 5. Kiểm thử

Chạy kiểm thử bằng lệnh:

```bash
php artisan test
```

## 6. Thông tin dự án

- Tên dự án: Blog cộng đồng
- Framework: Laravel 13
- Mục đích: Đồ án học tập.
