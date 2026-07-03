<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->unique()->words(2, true),
            'sku' => fake()->unique()->ean13(),
            'barcode' => fake()->unique()->ean13(),
            'description' => fake()->sentence(),
            'brand' => fake()->word(),
            'is_active' => true,
            'is_taxable' => true,
        ];
    }
}
