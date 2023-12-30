<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminDashboardPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admin_can_see_admin_dashboard_panel(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertRedirect(route('dashboard'))->assertSessionHas('error');

        $user->role = 'admin';
        $user->save();
        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertStatus(200)->assertViewHas(['posts', 'users']);
    }
}
