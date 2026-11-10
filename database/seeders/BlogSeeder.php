<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo tài khoản Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
            ]
        );

        // Tạo danh mục
        $categories = [
            'Công nghệ',
            'Lập trình',
            'Đời sống',
            'Giải trí',
            'Học tập',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name]
            );
        }

        // Tạo tags
        $tags = [
            'Laravel',
            'PHP',
            'MySQL',
            'JavaScript',
            'HTML CSS',
            'Công nghệ',
        ];

        foreach ($tags as $name) {
            Tag::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
