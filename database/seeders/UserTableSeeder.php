<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = new User;
        $user1->name = "Schopenhauer";
        $user1->email = "arthur@schopenhauer.com";
        $user1->password = "philosopher";
        $user1->save();
    }
}