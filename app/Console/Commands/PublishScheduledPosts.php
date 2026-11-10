<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish-scheduled';

    protected $description = 'Tự động đăng các bài viết đã đến lịch';

    public function handle(): int
    {
        // Lấy các bài viết đã đến thời gian đăng
        $posts = Post::where('status', 'scheduled')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->get();

        $count = 0;

        foreach ($posts as $post) {

            // Chuyển trạng thái sang đã đăng
            $post->update([
                'status' => 'published',
                'published_at' => now(),
            ]);

            // Ghi log
            Log::info('Scheduled post published', [
                'post_id' => $post->id,
                'title' => $post->title,
            ]);

            $count++;
        }

        $this->info("Đã tự động đăng {$count} bài viết.");

        return Command::SUCCESS;
    }
}
