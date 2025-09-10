<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Cars',
                'description' => 'All types of cars and related accessories.',
                'image' => 'categories/cars.png',
            ],
            [
                'name' => 'Electronics',
                'description' => 'Electronic gadgets and devices.',
                'image' => 'categories/cars.png',
            ],
            [
                'name' => 'Fashion',
                'description' => 'Clothing and accessories.',
                'image' => 'categories/cars.png',
            ],
        ];

        foreach ($categories as $data) {
            ProductCategory::create($data);
        }
    }
}
