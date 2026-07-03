<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sale>
 */
class SaleFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 10, 500);
        $discount = fake()->randomFloat(2, 0, $subtotal * 0.2);
        $tax = fake()->randomFloat(2, 0, $subtotal * 0.1);
        $total = $subtotal - $discount + $tax;

        return [
            'invoice_number' => Sale::generateInvoiceNumber(),
            'user_id' => User::factory(),
            'customer_name' => fake()->optional()->name(),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => fake()->randomElement(['cash', 'kbzpay']),
            'amount_paid' => $total,
            'change_amount' => 0,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
