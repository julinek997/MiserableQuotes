<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $post1 = new Post;
        $post1->post_body="meow meow";
        $post1->user_id = 1;
        $post1->save();

        Post::factory()->count(10)->create();
    }
}