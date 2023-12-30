<?php

namespace Database\Factories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
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
        $commentIds = DB::table('comments')->pluck('id');

        $arrLength = count($commentIds) / 2;
        for ($i = 0; $i < $arrLength; $i++) {
            $commentIds[$arrLength + $i] = null;
        }

        return [
            'post_id' => fake()->randomElement($postIds),
            'user_id' => fake()->randomElement($userIds),
            'parent_id' => count($commentIds) ? fake()->randomElement($commentIds) : null,
            'content' => fake()->sentences(3, true)
        ];
    }
}
