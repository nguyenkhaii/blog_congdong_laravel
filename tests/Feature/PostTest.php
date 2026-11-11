<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Mail\CommentPostedMail;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_post_list_displays_published_posts(): void
    {
        $post = Post::factory()->create();

        $response = $this->get(route('posts.index'));

        $response->assertStatus(200);
        $response->assertSee($post->title);
    }

    public function test_public_post_detail_displays_correct_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->get(route('posts.show', $post->slug));

        $response->assertStatus(200);
        $response->assertSee($post->title);
    }

    public function test_draft_post_cannot_be_viewed_by_guest(): void
    {
        $post = Post::factory()->create([
            'status' => 'draft',
            'published_at' => null,
        ]);

        $response = $this->get(route('posts.show', $post->slug));

        $response->assertStatus(404);
    }

    public function test_post_author_can_access_edit_page(): void
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('posts.edit', $post->id));

        $response->assertStatus(200);
    }

    public function test_other_member_cannot_access_edit_page(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $author->id,
        ]);

        $response = $this->actingAs($otherUser)
            ->get(route('posts.edit', $post->id));

        $response->assertStatus(403);
    }

    public function test_logged_in_member_can_comment_on_published_post(): void
    {
        Mail::fake();

        $author = User::factory()->create();
        $member = User::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $author->id,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->actingAs($member)
            ->post(route('comments.store', $post->id), [
                'content' => 'Đây là bình luận kiểm thử.',
            ]);

        $response->assertRedirect(route('posts.show', $post->slug));

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $member->id,
            'content' => 'Đây là bình luận kiểm thử.',
        ]);

        Mail::assertQueued(CommentPostedMail::class);
    }

    public function test_guest_cannot_comment_on_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->post(route('comments.store', $post->id), [
            'content' => 'Bình luận của khách.',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('comments', [
            'post_id' => $post->id,
            'content' => 'Bình luận của khách.',
        ]);
    }
}
