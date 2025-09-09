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
        // Define possible extensions
        $extensions = ['jpg', 'webp', 'png'];
        // Randomly pick an extension for each product
        $extension = $extensions[array_rand($extensions)];
        $categories = ProductCategory::all();
        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Run ProductCategorySeeder first.');
            return;
        }
        for ($i = 1; $i <= 70; $i++) {
            $name = "Demo Product $i";
            Product::create([
                'name' => $name,
                'title' => $name,
                'slug' => Str::slug($name) . '-' . $i,
                'summary' => "Summary of $name",
                'short_description' => "This is a short description of $name",
                'description' => "This is a detailed description of $name",
                'long_description' => "This is a long description of $name with more details and specifications.",
                'photo' => "products/product" . ($i % 5 + 1) . "." . $extension,
                'featured_image' => "products/product" . ($i % 5 + 1) . "." . $extension,
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

        $this->command->info('50 unique demo products seeded successfully.');
    }
}