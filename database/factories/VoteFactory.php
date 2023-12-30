<?php

namespace Database\Factories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vote>
 */
class VoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = DB::table('users')->pluck('id');
        $postIds = DB::table('posts')->pluck('id');
        return [
            'user_id' => fake()->randomElement($userIds),
            'post_id' => fake()->randomElement($postIds),
            'vote' => fake()->randomElement([1, 2]),
        ];
    }
}
