<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_edit_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);
        
        $response = $this->actingAs($user)->get(route('post.edit', $post));

        $response->assertStatus(200);
    }

    public function test_post_edit_page_is_not_displayed_for_unauthenticated_user(): void
    {
        $post = Post::factory()->imageless()->create([
            'user_id' => 1,
        ]);

        $response = $this->get(route('post.edit', $post));

        $response->assertRedirect(route('login'));
    }

    public function test_user_cannot_view_edit_page_of_other_user_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->imageless()->create([
            'user_id' => $user->id + 1,
        ]);

        $response = $this->actingAs($user)->get(route('post.edit', $post));

        $response->assertRedirect(route('dashboard'))->assertSessionHas('error');
        $this->assertSame(session('error'), 'You don\'t have access to that page.');
    }

    public function test_admin_can_view_edit_page_for_all_posts(): void
    {
        $user = User::factory()->create([
            'role' => 'admin'
        ]);
        $post = Post::factory()->imageless()->create([
            'user_id' => $user->id + 1,
        ]);

        $response = $this->actingAs($user)->get(route('post.edit', $post));

        $response->assertStatus(200);
    }

    public function test_user_can_update_his_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->imageless()->create([
            'title' => 'Original Post Title',
            'body' => 'Original Post Body Original Post Body Original Post Body Original Post Body Original Post Body Original Post Body Original Post Body',
        ]);

        $updatedData = [
            'title' => 'New Post Title',
            'body' => 'New Post Title New Post Title New Post Title New Post Title New Post Title New Post Title New Post Title New Post Title'
        ];

        $response = $this->actingAs($user)->post(route('post.update', $post), $updatedData);

        $response->assertRedirect()->assertSessionHas('success');
        $this->assertSame($post->fresh()->title, 'New Post Title');
        $this->assertSame($post->fresh()->body, 'New Post Title New Post Title New Post Title New Post Title New Post Title New Post Title New Post Title New Post Title');
    }

    public function test_user_cannot_update_other_users_posts(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->imageless()->create([
            'user_id' => $user->id + 1,
            'title' => 'Original post title',
        ]);

        $updatedPost = $post;
        $updatedPost['title'] = 'Updated post title';

        $response = $this->actingAs($user)->post(route('post.update', $post), $updatedPost->toArray());

        $response->assertRedirect(route('dashboard'))->assertSessionHas('error');
        $this->assertSame($post->fresh()->title, 'Original post title');
        $this->assertSame(session('error'), 'Unauthorized action.');
    }

    public function test_user_can_delete_post_image(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('post_image.png');
        $image->store('post_images', 'public');

        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'image' => 'post_images/' . $image->hashName()
        ]);

        Storage::disk('public')->assertExists('post_images/' . $image->hashName());

        $updatedPost = array_merge($post->toArray(), ['imageAction' => 'delete']);
        $updatedPost['image'] = null;

        $response = $this->actingAs($user)->post(route('post.update', $post), $updatedPost);

        $response->assertRedirect(route('post.view', $post));
        Storage::disk('public')->assertMissing('post_images/' . $image->hashName());
    }

    public function test_user_can_change_post_image(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('post_image.png');
        $image->store('post_images', 'public');

        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'image' => 'post_images/' . $image->hashName()
        ]);

        Storage::disk('public')->assertExists('post_images/' . $image->hashName());

        $updatedPost = array_merge($post->toArray(), ['imageAction' => 'delete']);
        $updatedPost['image'] = UploadedFile::fake()->image('new_post_image.png');

        $response = $this->actingAs($user)->post(route('post.update', $post), $updatedPost);

        $response->assertRedirect(route('post.view', $post))->assertSessionHas('success');
        Storage::disk('public')->assertMissing('post_images/' . $image->hashName());
        Storage::disk('public')->assertExists($post->fresh()->image);
    }
}
