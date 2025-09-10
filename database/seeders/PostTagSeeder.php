<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PostTag;
use Illuminate\Support\Str;

class PostTagSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            PostTag::create([
                'title'  => 'Tag ' . $i,
                'slug'   => Str::slug('Tag ' . $i),
                'status' => rand(0, 1) ? 'active' : 'inactive',
            ]);
        }
    }
}
