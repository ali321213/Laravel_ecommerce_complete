<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $imageNames = [
            'aaniasharmaa.jpg',
            'aaniasharmaa1.jpg',
            'aaniasharmaa2.jpg',
            'aaniasharmaa3.jpg',
            'aaniasharmaa4.jpg',
            'aaniasharmaa5.jpg',
            'aaniasharmaa6.jpg',
            'aaniasharmaa7.jpg',
            'aaniasharmaa8.jpg',
            'aaniasharmaa9.jpg',
            'aaniasharmaa10.jpg',
            'aaniasharmaa11.jpg',
            'aaniasharmaa12.jpg',
            'aaniasharmaa13.jpg',
            'aaniasharmaa14.jpg',
            'aaniasharmaa15.jpg',
            'aaniasharmaa16.jpg',
            'aaniasharmaa17.jpg',
            'aaniasharmaa18.jpg'
            // 'product.jpg', 'product1.jpg', 'product2.jpg', 'product3.png',
        ];
        $categories = ProductCategory::all();
        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Run ProductCategorySeeder first.');
            return;
        }
        for ($i = 1; $i <= 70; $i++) {
            $name = "Demo Product $i";
            $image = $imageNames[$i % count($imageNames)];
            Product::create([
                // 'name' => $name,
                'title' => $name,
                'slug' => Str::slug($name) . '-' . $i,
                'summary' => "Summary of $name",
                'short_description' => "This is a short description of $name",
                'description' => "This is a detailed description of $name",
                'long_description' => "This is a long description of $name with more details and specifications.",
                'photo' => "products/aaniasharmaa/$image",
                'featured_image' => "products/$image",
                'stock' => rand(5, 100),
                'size' => ['S', 'M', 'L', 'XL'][array_rand(['S', 'M', 'L', 'XL'])],
                'condition' => 'default',
                'status' => 'active',
                'price' => rand(100, 1000),
                'discount' => rand(0, 30),
                'is_variable' => false,
                'is_grouped' => false,
                'is_simple' => true,
                'is_featured' => (bool)rand(0, 1),
                'category_id' => $categories->random()->id,
            ]);
        }
        // $this->command->info('70 unique demo products seeded successfully.');
    }
}
