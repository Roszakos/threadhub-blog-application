<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoteDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_their_vote(): void
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

        $this->assertNotNull($vote->vote);

        $response = $this->actingAs($user)->delete(route('vote.destroy', $post->id));

        $response->assertStatus(200)->assertSee('success');
        $this->assertNull($vote->fresh());
    }

    public function test_unauthenticated_user_cannot_delete_a_vote(): void
    {
        $vote = Vote::factory()->create([
            'user_id' => 1,
            'post_id' => 1,
            'vote' => 1
        ]);

        $this->assertNotNull($vote->vote);

        $response = $this->delete(route('vote.destroy', 1));

        $response->assertRedirect(route('login'));
        $this->assertNotNull($vote->fresh());
    }

    public function test_user_cannot_delete_other_user_vote(): void
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

        $this->assertNotNull($vote->vote);

        $response = $this->actingAs($user)->delete(route('vote.destroy', $post->id));

        $response->assertRedirect(route('dashboard'))->assertSessionHas('error');
        $this->assertNotNull($vote->fresh());
    }
}
