<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Unit>
 */
class UnitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'name' => fake()->word(),
            'quantity' => 1,
            'price' => fake()->randomFloat(2, 5, 500),
        ];
    }

    public function bottle(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Bottle',
            'quantity' => 1,
            'price' => fake()->randomFloat(2, 5, 100),
        ]);
    }

    public function case(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Case',
            'quantity' => 12,
            'price' => fake()->randomFloat(2, 50, 500),
        ]);
    }
}
