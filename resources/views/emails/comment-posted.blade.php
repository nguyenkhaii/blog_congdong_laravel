<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Bình luận mới</title>
</head>

<body>

    <h2>Xin chào {{ $comment->post->user->name ?? 'bạn' }}!</h2>

    <p>
        Bài viết của bạn vừa nhận được một bình luận mới.
    </p>

    <hr>

    <h3>{{ $comment->post->title }}</h3>

    <p>
        <strong>Người bình luận:</strong>
        {{ $comment->user->name ?? 'Người dùng đã bị xóa' }}
    </p>

    <p>
        <strong>Nội dung bình luận:</strong>
    </p>

    <blockquote>
        {{ $comment->content }}
    </blockquote>

    <p>
        Hãy đăng nhập vào Blog cộng đồng để xem và phản hồi bình luận.
    </p>

    <hr>

    <p>
        Email thông báo tự động từ Website Blog cộng đồng.
    </p>

</body>

</html>
