<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            usersSeeder::class,
            CategorySeeder::class,
            PostsSeeder::class,
            category_subSeeder::class,
            CommentsSeeder::class,
            LikeSeeder::class,
        ]);
    }
}
