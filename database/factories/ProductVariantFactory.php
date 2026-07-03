<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'unit_id' => Unit::factory(),
            'units_per_package' => 1,
            'cost_price' => fake()->randomFloat(2, 1, 50),
            'selling_price' => fake()->randomFloat(2, 2, 100),
            'stock_quantity' => fake()->numberBetween(0, 200),
            'min_stock_level' => 5,
            'is_active' => true,
        ];
    }

    public function withStock(int $quantity = 50): static
    {
        return $this->state(fn () => [
            'stock_quantity' => $quantity,
        ]);
    }

    public function lowStock(): static
    {
        return $this->state(fn () => [
            'stock_quantity' => 2,
            'min_stock_level' => 10,
        ]);
    }
}
