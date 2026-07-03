<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Beer', 'slug' => 'beer', 'icon' => 'fa-beer', 'color' => '#f4a460'],
            ['name' => 'Soft Drinks', 'slug' => 'soft-drinks', 'icon' => 'fa-cola', 'color' => '#dc3545'],
            ['name' => 'Candy', 'slug' => 'candy', 'icon' => 'fa-candy-cane', 'color' => '#ff69b4'],
            ['name' => 'Snacks', 'slug' => 'snacks', 'icon' => 'fa-cookie', 'color' => '#ffa500'],
            ['name' => 'Water', 'slug' => 'water', 'icon' => 'fa-water', 'color' => '#0dcaf0'],
            ['name' => 'Juice', 'slug' => 'juice', 'icon' => 'fa-apple-alt', 'color' => '#28a745'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
