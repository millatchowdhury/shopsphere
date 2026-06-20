<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'name' => Str::title($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'price' => fake()->randomFloat(2, 19, 499),
            'discount_price' => fake()->boolean(35) ? fake()->randomFloat(2, 9, 249) : null,
            'stock_quantity' => fake()->numberBetween(5, 80),
            'sku' => 'SKU-'.fake()->unique()->bothify('??####'),
            'description' => fake()->paragraph(4),
            'is_featured' => fake()->boolean(35),
            'status' => true,
        ];
    }
}
