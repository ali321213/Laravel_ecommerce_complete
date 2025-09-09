<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $parentCategories = [];

        for ($i = 1; $i <= 5; $i++) {
            $title = 'Parent Category ' . $i;
            $parentCategories[] = Category::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'summary' => 'This is a summary for ' . $title,
                'photo' => 'products/product1.jpg',
                'is_parent' => true,
                'parent_id' => null,
                'added_by' => null,
                'status' => 'active',
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            $title = 'Child Category ' . $i;
            Category::create([
                'title' => $title,
                'slug' => Str::slug($title),
                'summary' => 'This is a summary for ' . $title,
                'photo' => 'products/product1.jpg',
                'is_parent' => false,
                'parent_id' => $parentCategories[array_rand($parentCategories)]->id,
                'added_by' => null,
                'status' => 'active',
            ]);
        }
    }
}
