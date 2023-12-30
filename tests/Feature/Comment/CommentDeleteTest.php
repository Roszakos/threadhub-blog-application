<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_delete_their_comment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => 1
        ]);

        $response = $this->actingAs($user)->delete(route('comment.destroy', $comment));

        $response->assertStatus(200)->assertSee('success');
        $this->assertNull($comment->fresh());
    }

    public function test_unauthenticated_user_cannot_delete_comments(): void
    {
        $comment = Comment::factory()->create([
            'user_id' => 1,
            'post_id' => 1
        ]);

        $response = $this->delete(route('comment.destroy', $comment));

        $response->assertRedirect(route('login'));
        $this->assertNotNull($comment->fresh());
    }

    public function test_user_cannot_delete_other_user_comments(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id + 1,
            'post_id' => 1
        ]);

        $response = $this->actingAs($user)->delete(route('comment.destroy', $comment));

        $response->assertRedirect(route('dashboard'))->assertSessionHas('error');
        $this->assertNotNull($comment->fresh());
    }

    public function test_admin_can_delete_other_user_comments(): void
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $comment = Comment::factory()->create([
            'user_id' => $user->id + 1,
            'post_id' => 1
        ]);

        $response = $this->actingAs($user)->delete(route('comment.destroy', $comment));

        $response->assertStatus(200)->assertSee('success');
        $this->assertNull($comment->fresh());
    }

    public function test_comment_replies_are_deleted_along_with_the_comment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => 1
        ]);
        $reply = Comment::factory()->create([
            'post_id' => 1,
            'user_id' => 2,
            'parent_id' => $comment->id
        ]);
        $replyReplies = Comment::factory(3)->create([
            'post_id' => 1,
            'user_id' => 3,
            'parent_id' => $reply->id
        ]);

        $this->assertNotNull($reply);
        $this->assertNotEmpty($replyReplies);

        $response = $this->actingAs($user)->delete(route('comment.destroy', $comment));

        $response->assertStatus(200)->assertSee('success');
        $this->assertNull($comment->fresh());
        $this->assertNull($reply->fresh());
        $this->assertEmpty($replyReplies->fresh());
    }

}
