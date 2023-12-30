<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_edit_their_comment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => 1,
            'content' => 'original comment'
        ]);

        $data = [
            'content' => 'updated comment'
        ];

        $response = $this->actingAs($user)->put(route('comment.update', $comment), $data);

        $response->assertStatus(201)->assertSee('success');
        $this->assertSame($comment->fresh()->content, 'updated comment');
    }

    public function test_unauthenticated_user_cannot_edit_comments(): void
    {
        $comment = Comment::factory()->create([
            'user_id' => 1,
            'post_id' => 1,
            'content' => 'original comment'
        ]);

        $data = [
            'content' => 'updated comment'
        ];

        $response = $this->put(route('comment.update', $comment), $data);

        $response->assertRedirect(route('login'));
        $this->assertSame($comment->fresh()->content, 'original comment');
    }

    public function test_user_cannot_edit_other_user_comment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id + 1,
            'post_id' => 1,
            'content' => 'original comment'
        ]);

        $data = [
            'content' => 'updated comment'
        ];

        $response = $this->actingAs($user)->put(route('comment.update', $comment), $data);

        $response->assertRedirect(route('dashboard'))->assertSessionHas('error');
        $this->assertSame($comment->fresh()->content, 'original comment');
    }
}
