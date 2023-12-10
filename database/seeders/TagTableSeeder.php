<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tag1 = new Tag;
        $tag1->tag_body = "nice";
        $tag1->save();

        Tag::factory()->count(10)->create();
    }
}