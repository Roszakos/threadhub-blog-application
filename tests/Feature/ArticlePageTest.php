<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticlePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_page_can_be_rendered(): void
    {
        $response = $this->get(route('post.articles'));
        
        $response->assertStatus(200)->assertViewHas(['posts', 'search']);
    }

    public function test_search_bar_works_properly(): void
    {
        $user = User::factory()->create();
        Post::factory(5)->create([
            'title' => 'not what im looking for',
            'user_id' => $user->id
        ]);
        Post::factory()->create([
            'title' => 'this exact title',
            'user_id' => $user->id
        ]);

        $response = $this->get(route('post.articles') . '?search=this+exact+title');
       
        $response->assertStatus(200)->assertViewHas(['posts', 'search']);
        $this->assertEquals(1, count($response->viewData('posts')));
        $this->assertSame($response->viewData('search'), 'this exact title');
        $this->assertSame($response->viewData('posts')[0]->title, 'this exact title');
    }

    public function test_articles_are_paginated_properly(): void
    {
        $user = User::factory()->create();
        Post::factory(15)->create([
            'user_id' => $user->id
        ]);

        $response = $this->get(route('post.articles'));

        $response->assertStatus(200)->assertViewHas(['posts', 'search']);
        $this->assertEquals(count($response->viewData('posts')), 10);
    }
}
