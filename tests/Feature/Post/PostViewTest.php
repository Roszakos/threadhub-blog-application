<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostViewTest extends TestCase
{
    use RefreshDatabase;
    public function test_post_view_page_can_be_rendered(): void
    {
        $post = Post::factory()->create([
            'user_id' => 1
        ]);

        $response = $this->get(route('post.view', $post));

        $response->assertStatus(200)->assertViewHas([
            'post', 
            'vote', 
            'upvotes',
            'downvotes',
            'comments',
            'commentsAmount',
            'isOwner'
        ]);

        $this->assertSame($post->title, $response->viewData('post')->title);
        $this->assertSame($post->body, $response->viewData('post')->body);
    }
}
