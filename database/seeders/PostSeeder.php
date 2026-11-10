<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy tài khoản Admin làm tác giả mẫu
        $user = User::where('email', 'admin@gmail.com')->first();

        // Lấy danh mục
        $categories = Category::all();

        // Lấy tags
        $tags = Tag::all();

        // Dữ liệu bài viết mẫu
        $posts = [
            [
                'title' => 'Giới thiệu về Laravel 13',
                'content' => 'Laravel là một framework PHP mạnh mẽ, giúp xây dựng ứng dụng web nhanh chóng và hiệu quả.',
                'category' => 'Lập trình',
                'tags' => ['Laravel', 'PHP'],
            ],
            [
                'title' => 'Tìm hiểu về cơ sở dữ liệu MySQL',
                'content' => 'MySQL là hệ quản trị cơ sở dữ liệu quan hệ phổ biến, được sử dụng rộng rãi trong các ứng dụng web.',
                'category' => 'Công nghệ',
                'tags' => ['MySQL', 'Công nghệ'],
            ],
            [
                'title' => 'HTML và CSS dành cho người mới bắt đầu',
                'content' => 'HTML dùng để xây dựng cấu trúc trang web, trong khi CSS giúp định dạng giao diện.',
                'category' => 'Lập trình',
                'tags' => ['HTML CSS', 'JavaScript'],
            ],
            [
                'title' => 'JavaScript có vai trò gì trong website?',
                'content' => 'JavaScript giúp website có thể tương tác với người dùng và xử lý các thao tác ngay trên trình duyệt.',
                'category' => 'Học tập',
                'tags' => ['JavaScript'],
            ],
            [
                'title' => 'Xu hướng công nghệ hiện nay',
                'content' => 'Công nghệ đang phát triển nhanh chóng với trí tuệ nhân tạo, điện toán đám mây và nhiều lĩnh vực mới.',
                'category' => 'Công nghệ',
                'tags' => ['Công nghệ'],
            ],
        ];

        foreach ($posts as $item) {

            $category = Category::where('name', $item['category'])->first();

            $post = Post::firstOrCreate(
                ['slug' => Str::slug($item['title'])],
                [
                    'title' => $item['title'],
                    'content' => $item['content'],
                    'user_id' => $user->id,
                    'category_id' => $category?->id,
                    'status' => 'published',
                    'published_at' => now(),
                ]
            );

            // Gắn tags cho bài viết
            foreach ($item['tags'] as $tagName) {

                $tag = Tag::where('name', $tagName)->first();

                if ($tag) {
                    $post->tags()->syncWithoutDetaching([$tag->id]);
                }
            }
        }
    }
}
