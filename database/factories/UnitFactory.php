<?php

namespace Database\Factories;

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
            'name' => fake()->word(),
        ];
    }

    public function bottle(): static
    {
        return $this->state(fn () => [
            'name' => 'Bottle',
        ]);
    }

    public function can(): static
    {
        return $this->state(fn () => [
            'name' => 'Can',
        ]);
    }

    public function pack(): static
    {
        return $this->state(fn () => [
            'name' => 'Pack',
        ]);
    }

    public function piece(): static
    {
        return $this->state(fn () => [
            'name' => 'Piece',
        ]);
    }
}
