<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('users')->upsert([
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], 'email');

        DB::table('categories')->upsert([
            ['id' => 1, 'name' => 'Beverages', 'description' => 'Drinks and refreshments', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Snacks', 'description' => 'Light meals and munchies', 'created_at' => $now, 'updated_at' => $now],
        ], 'id');

        DB::table('products')->upsert([
            [
                'id' => 1,
                'category_id' => 1,
                'name' => 'Cola',
                'description' => 'Carbonated cola drink',
                'sku' => 'BEV-COLA-001',
                'stock' => 200,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'category_id' => 1,
                'name' => 'Spring Water',
                'description' => 'Natural spring water',
                'sku' => 'BEV-WATER-001',
                'stock' => 300,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'category_id' => 2,
                'name' => 'Potato Chips',
                'description' => 'Classic salted chips',
                'sku' => 'SNK-CHIPS-001',
                'stock' => 150,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ], 'id');

        DB::table('units')->upsert([
            ['id' => 1, 'product_id' => 1, 'name' => 'Bottle', 'quantity' => 1, 'price' => 1.50, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'product_id' => 1, 'name' => 'Case (12)', 'quantity' => 12, 'price' => 15.00, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'product_id' => 1, 'name' => 'Can', 'quantity' => 1, 'price' => 1.00, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'product_id' => 2, 'name' => 'Bottle', 'quantity' => 1, 'price' => 1.00, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'product_id' => 2, 'name' => 'Case (12)', 'quantity' => 12, 'price' => 10.00, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'product_id' => 2, 'name' => 'Gallon', 'quantity' => 1, 'price' => 3.00, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'product_id' => 3, 'name' => 'Small Pack', 'quantity' => 1, 'price' => 2.00, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'product_id' => 3, 'name' => 'Family Pack', 'quantity' => 1, 'price' => 5.00, 'created_at' => $now, 'updated_at' => $now],
        ], 'id');
    }

    public function down(): void
    {
        DB::table('units')->whereIn('product_id', function ($q) {
            $q->select('id')->from('products')->whereIn('sku', ['BEV-COLA-001', 'BEV-WATER-001', 'SNK-CHIPS-001']);
        })->delete();

        DB::table('products')->whereIn('sku', ['BEV-COLA-001', 'BEV-WATER-001', 'SNK-CHIPS-001'])->delete();

        DB::table('categories')->whereIn('name', ['Beverages', 'Snacks'])->delete();

        DB::table('users')->where('email', 'test@example.com')->delete();
    }
};
