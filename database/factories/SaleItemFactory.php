<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SaleItem>
 */
class SaleItemFactory extends Factory
{
    public function definition(): array
    {
        $unit = Unit::factory()->create();

        return [
            'sale_id' => Sale::factory(),
            'product_id' => $unit->product_id,
            'unit_id' => $unit->id,
            'quantity' => fake()->numberBetween(1, 10),
            'unit_price' => $unit->price,
            'subtotal' => fn (array $attrs) => $attrs['unit_price'] * $attrs['quantity'],
        ];
    }
}
