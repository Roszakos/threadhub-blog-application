<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoteCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_a_vote(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => 1
        ]);

        $dataUpvote = [
            'postId' => $post->id,
            'vote' => 1
        ];
        $dataDownvote = [
            'postId' => $post->id,
            'vote' => 2
        ];
        

        $responseUpvote = $this->actingAs($user)->post(route('vote.update'), $dataUpvote);
        $responseDownvote = $this->actingAs($user)->post(route('vote.update'), $dataDownvote);

        $responseUpvote->assertStatus(200)->assertSee('success');
        $responseDownvote->assertStatus(200)->assertSee('success');
    }

    public function test_unauthenticated_user_cannot_add_a_vote(): void
    {
        $post = Post::factory()->create([
            'user_id' => 1
        ]);

        $data = [
            'postId' => $post->id,
            'vote' => 1
        ];

        $response = $this->post(route('vote.update'), $data);

        $response->assertRedirect(route('login'));
    }

    public function test_user_cannot_add_vote_other_than_1_and_2(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => 1
        ]);

        $data = [
            'postId' => $post->id,
            'vote' => 3
        ];

        $response = $this->actingAs($user)->post(route('vote.update'), $data);

        $response->assertStatus(302);
    }
}
