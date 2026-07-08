<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SaleItem>
 */
class SaleItemFactory extends Factory
{
    public function definition(): array
    {
        $variant = ProductVariant::factory()->create();
        $quantity = fake()->numberBetween(1, 10);
        $unitPrice = $variant->selling_price;

        return [
            'sale_id' => Sale::factory(),
            'product_variant_id' => $variant->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'cost_price' => $variant->cost_price,
            'discount' => 0,
            'total_price' => $unitPrice * $quantity,
            'tax_amount' => 0,
        ];
    }
}
