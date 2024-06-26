<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)
        //                 ->has(\App\Models\Post::factory(3))
        //                 ->create();
        // \App\Models\Comment::factory(10)->create();
        // \App\Models\Comment::factory(10)->create();
        // \App\Models\Comment::factory(20)->create();
        // \App\Models\Comment::factory(20)->create();
        // \App\Models\Comment::factory(30)->create();
        // \App\Models\Comment::factory(30)->create();
        // \App\Models\Vote::factory(1000)->create();

        \App\Models\User::factory()->create([
            'nickname' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
