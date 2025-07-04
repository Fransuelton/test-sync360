<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'profile_image' => Str::random(10) . '.png',
            'full_name' => Str::random(10),
            'age' => rand(18, 65),
            'street' => Str::random(10),
            'neighborhood' => Str::random(10),
            'state' => Str::random(10),
            'biography' => Str::random(50),
        ]);
    }
}
