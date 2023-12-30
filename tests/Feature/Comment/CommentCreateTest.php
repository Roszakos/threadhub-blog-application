<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $data = [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => 'bla bla bla'
        ];

        $response = $this->actingAs($user)->post(route('comment.store'), $data);

        $response->assertStatus(201);
        $response->assertJson([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'bla bla bla',
        ]);
    }

    public function test_unauthenticated_user_can_create_a_comment(): void
    {
        $post = Post::factory()->create([
            'user_id' => 1
        ]);

        $data = [
            'post_id' => $post->id,
            'content' => 'bla bla bla',
            'author' => 'Marek'
        ];

        $response = $this->post(route('comment.store'), $data);

        $response->assertStatus(201);
        $response->assertJson([
            'post_id' => $post->id,
            'author' => 'Marek',
            'content' => 'bla bla bla',
        ]);
    }

    public function test_unauthenticated_user_cannot_create_a_comment_without_author_field(): void
    {
        $post = Post::factory()->create([
            'user_id' => 1
        ]);

        $data = [
            'post_id' => $post->id,
            'content' => 'bla bla bla'
        ];

        $response = $this->post(route('comment.store'), $data);

        $response->assertUnprocessable();
    }
}
