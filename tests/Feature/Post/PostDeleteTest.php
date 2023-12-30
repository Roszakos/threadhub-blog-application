<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_their_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->delete(route('post.destroy', $post));

        $response->assertRedirect(route('dashboard'))->assertSessionHas('success');
        $this->assertNull($post->fresh());
    }

    public function test_unauthenticated_user_cannot_delete_post(): void
    {
        $post = Post::factory()->create([
            'user_id' => 1
        ]);

        $response = $this->delete(route('post.destroy', $post));

        $response->assertRedirect(route('login'));
        $this->assertNotNull($post->fresh());
    }

    public function test_user_cannot_delete_other_user_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id + 1
        ]);

        $response = $this->actingAs($user)->delete(route('post.destroy', $post));

        $response->assertRedirect(route('dashboard'))->assertSessionHas('error');
        $this->assertNotNull($post->fresh());
    }

    public function test_admin_can_delete_every_post(): void
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $post = Post::factory()->create([
            'user_id' => $user->id + 1
        ]);

        $response = $this->actingAs($user)->delete(route('post.destroy', $post));

        $response->assertRedirect(route('dashboard'))->assertSessionHas('success');
        $this->assertNull($post->fresh());
    }

    public function test_posts_comments_and_votes_are_deleted_along_with_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id
        ]);
        $comments = \App\Models\Comment::factory(3)->create([
            'post_id' => $post->id
        ]);
        $votes = \App\Models\Vote::factory(3)->create([
            'post_id' => $post->id
        ]);
        
        $this->assertNotEmpty($comments);
        $this->assertNotEmpty($votes);
        $this->assertNotNull($post);

        $response = $this->actingAs($user)->delete(route('post.destroy', $post));

        $response->assertRedirect(route('dashboard'))->assertSessionHas('success');
        $this->assertNull($post->fresh());
        $this->assertEmpty($comments->fresh());
        $this->assertEmpty($votes->fresh());
    }
}
