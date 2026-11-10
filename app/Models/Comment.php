<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
    ];

    // Bình luận thuộc về một bài viết
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Bình luận thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
