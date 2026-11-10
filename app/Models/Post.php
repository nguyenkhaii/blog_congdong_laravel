<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'category_id',
        'slug',
        'image',
        'status',
        'published_at',
        'scheduled_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'scheduled_at' => 'datetime',
        ];
    }

    // Bài viết thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Bài viết thuộc về một danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Bài viết có nhiều tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // Bài viết có nhiều bình luận
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
