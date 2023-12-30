<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoteUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_change_their_vote(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id
        ]);
        $vote = Vote::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'vote' => 1
        ]);

        $this->assertSame($vote->vote, 1);

        $data = [
            'vote' => 2,
            'postId' => $post->id,
        ];


        $response = $this->actingAs($user)->put(route('vote.store'), $data);

        $response->assertStatus(200)->assertSee('success');
        $this->assertSame($vote->fresh()->vote, 2);
    }

    public function test_unauthenticated_user_cannot_update_a_vote(): void
    {
        $vote = Vote::factory()->create([
            'user_id' => 1,
            'post_id' => 1,
            'vote' => 1
        ]);

        $data = [
            'vote' => 2,
            'postId' => 1,
        ];


        $response = $this->put(route('vote.store'), $data);

        $response->assertRedirect(route('login'));
        $this->assertSame($vote->fresh()->vote, 1);
    }

    public function test_user_cannot_change_other_user_vote(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id
        ]);
        $vote = Vote::factory()->create([
            'user_id' => $user->id + 1,
            'post_id' => $post->id,
            'vote' => 1
        ]);

        $data = [
            'vote' => 2,
            'postId' => $post->id,
        ];


        $response = $this->actingAs($user)->put(route('vote.store'), $data);

        $response->assertRedirect(route('dashboard'))->assertSessionHas('error');
        $this->assertSame($vote->fresh()->vote, 1);
    }
}
