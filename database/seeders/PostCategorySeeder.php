<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PostCategory;
use Illuminate\Support\Str;

class PostCategorySeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            PostCategory::create([
                'title'  => 'Category ' . $i,
                'slug'   => Str::slug('Category ' . $i),
                'status' => rand(0, 1) ? 'active' : 'inactive',
            ]);
        }
    }
}
