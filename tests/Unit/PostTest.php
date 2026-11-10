<?php

namespace Tests\Unit;

use App\Models\Post;
use Tests\TestCase;

class PostTest extends TestCase
{
    // Kiểm tra Post có thể lưu tiêu đề và nội dung
    public function test_post_can_store_title_and_content(): void
    {
        $post = new Post([
            'title' => 'Bài viết kiểm thử',
            'content' => 'Nội dung bài viết kiểm thử.',
        ]);

        $this->assertEquals('Bài viết kiểm thử', $post->title);
        $this->assertEquals('Nội dung bài viết kiểm thử.', $post->content);
    }

    // Kiểm tra ngày đăng được chuyển thành kiểu ngày giờ
    public function test_post_published_at_is_datetime(): void
    {
        $post = new Post([
            'published_at' => '2026-11-11 10:00:00',
        ]);

        $this->assertInstanceOf(
            \Illuminate\Support\Carbon::class,
            $post->published_at
        );
    }
}
