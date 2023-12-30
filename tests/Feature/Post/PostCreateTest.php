<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_post_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('post.create'));
        
        $response->assertStatus(200);
    }

    public function test_create_post_page_is_not_displayed_for_unauthenticated_user(): void
    {
        $response = $this->get(route('post.create'));

        $response->assertRedirect();
    }

    public function test_user_can_create_new_post(): void
    {
        $postsCount = DB::table('posts')->count();
        $user = User::factory()->create();

        $postData = [
            'title' => 'New Post',
            'body' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s'
        ];

        $response = $this->actingAs($user)->post(route('post.store'), $postData);

        $response->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseCount('posts', $postsCount + 1);
    }

    public function test_user_can_create_new_post_with_image(): void
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('post_image.png');

        $postsCount = DB::table('posts')->count();

        $user = User::factory()->create();

        $postData = [
            'title' => 'New Post',
            'body' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s',
            'image' => $image
        ];

        $response = $this->actingAs($user)->post(route('post.store'), $postData);

        $response->assertRedirect()->assertSessionHas('success');
        $this->assertDatabaseCount('posts', $postsCount + 1);
        Storage::disk('public')->assertExists('post_images/' . $image->hashName());
    }

    public function test_unauthenticated_user_cannot_create_new_post(): void
    {
        $response = $this->post(route('post.store'));
        $response->assertRedirect(route('login'));
    }
}
