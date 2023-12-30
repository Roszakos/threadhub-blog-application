<?php

namespace Database\Factories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = DB::table('users')->pluck('id');
        return [
            'user_id' => fake()->randomElement($userIds),
            'title' => fake()->sentence(),
            'body' => fake()->paragraphs(7, true),
            'views' => fake()->numberBetween(50, 15000)
        ];
    }

    public function imageless(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'image' => null,
            ];
        });
    }

    public function withImage(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'image' => str_replace('\\','/',str_replace('public/storage/', '', fake()->image('public/storage/post_images', 640, 480))),
            ];
        });
    }
}
