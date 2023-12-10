<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comment;

class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comment1 = new Comment;
        $comment1->comment_body="very nice";
        $comment1->save();

        Comment::factory()->count(10)->create();
    }
}
