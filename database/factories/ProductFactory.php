<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $title = $this->faker->unique()->words(3, true);

        return [
            'title'       => $title,
            'slug'        => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1, 10000),
            'summary'     => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'photo'       => 'products/' . $this->faker->randomElement([
                'product.jpg',
                'product1.jpg',
                'product2.jpg',
                'product3.jpg',
            ]),
            'stock'       => $this->faker->numberBetween(1, 100),
            'size'        => $this->faker->randomElement(['S', 'M', 'L']),
            'condition'   => $this->faker->randomElement(['new', 'popular', 'default']),
            'status'      => 'active',
            'price'       => $this->faker->randomFloat(2, 10, 1000),
            'discount'    => $this->faker->optional()->randomFloat(2, 1, 50),
            'is_featured' => $this->faker->boolean(20),
            'cat_id'      => null,
            'child_cat_id' => null,
            'brand_id'    => null,
        ];
    }
}
