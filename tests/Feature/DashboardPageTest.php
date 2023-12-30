<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardPageTest extends TestCase
{    
    use RefreshDatabase;

    public function test_dashboard_page_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertStatus(200)->assertViewHas(['posts']);
    }

    public function test_unauthenticated_user_cannot_see_dashboard_page(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }
}
