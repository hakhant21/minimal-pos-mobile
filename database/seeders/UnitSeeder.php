<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'Piece', 'is_sellable' => true],
            ['name' => 'Bottle', 'is_sellable' => true],
            ['name' => 'Can', 'is_sellable' => true],
            ['name' => 'Pack', 'is_sellable' => true],
            ['name' => 'Box', 'is_sellable' => true],
            ['name' => 'Case', 'is_sellable' => true],
            ['name' => 'Bulk', 'is_sellable' => false],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
