<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_can_be_rendered(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200)->assertViewHas(['posts', 'trendingPost'])->assertSee('Register');
    }

    public function test_home_page_is_rendered_properly_for_logged_in_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertStatus(200)->assertViewHas(['posts', 'trendingPost'])->assertDontSee('Register');
    }
}
