<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! app()->isLocal()) {
            return;
        }

        // Units
        $bottle = DB::table('units')->insertGetId(['name' => 'ပုလင်း', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('units')->insert(['name' => 'ဗူး', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('units')->insert(['name' => 'ကော်ဗူး', 'created_at' => now(), 'updated_at' => now()]);
        $shortCan = DB::table('units')->insertGetId(['name' => 'သံဗူး (တို)', 'created_at' => now(), 'updated_at' => now()]);
        $longCan = DB::table('units')->insertGetId(['name' => 'သံဗူး (ရှည်)', 'created_at' => now(), 'updated_at' => now()]);
        $pack = DB::table('units')->insertGetId(['name' => 'ထုတ်', 'created_at' => now(), 'updated_at' => now()]);
        $loin = DB::table('units')->insertGetId(['name' => 'လုံး', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('units')->insert(['name' => 'အိတ်', 'created_at' => now(), 'updated_at' => now()]);

        // Categories
        $beer = DB::table('categories')->insertGetId(['name' => 'ဘီယာ', 'slug' => 'beer', 'created_at' => now(), 'updated_at' => now()]);
        $softDrinks = DB::table('categories')->insertGetId(['name' => 'အချိုရှည်', 'slug' => 'soft-drinks', 'created_at' => now(), 'updated_at' => now()]);
        $cigarettes = DB::table('categories')->insertGetId(['name' => 'ဆေးလိပ်', 'slug' => 'cigarettes', 'created_at' => now(), 'updated_at' => now()]);
        $snacks = DB::table('categories')->insertGetId(['name' => 'မုန့်', 'slug' => 'snacks', 'created_at' => now(), 'updated_at' => now()]);
        $water = DB::table('categories')->insertGetId(['name' => 'ရေသန့်', 'slug' => 'water', 'created_at' => now(), 'updated_at' => now()]);

        // Products
        $tiger = DB::table('products')->insertGetId([
            'category_id' => $beer, 'name' => 'Tiger Beer', 'sku' => 'TIGER001', 'brand' => 'Tiger',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $coke = DB::table('products')->insertGetId([
            'category_id' => $softDrinks, 'name' => 'Coca-Cola', 'sku' => 'COKE001', 'brand' => 'Coca-Cola',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $myanmarRed = DB::table('products')->insertGetId([
            'category_id' => $cigarettes, 'name' => 'Myanmar Red', 'sku' => 'MMRED001', 'brand' => 'Myanmar',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $luckyStrike = DB::table('products')->insertGetId([
            'category_id' => $cigarettes, 'name' => 'Lucky Strike', 'sku' => 'LSTRIKE001', 'brand' => 'Lucky Strike',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $lays = DB::table('products')->insertGetId([
            'category_id' => $snacks, 'name' => "Lay's Classic Chips", 'sku' => 'LAYS001', 'brand' => "Lay's",
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $evian = DB::table('products')->insertGetId([
            'category_id' => $water, 'name' => 'Evian Water', 'sku' => 'EVIAN001', 'brand' => 'Evian',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // Product Variants
        $variants = [
            ['product_id' => $tiger, 'unit_id' => $bottle, 'category_id' => $beer, 'stock_quantity' => 100, 'min_stock_level' => 10],
            ['product_id' => $tiger, 'unit_id' => $longCan, 'category_id' => $beer, 'stock_quantity' => 150, 'min_stock_level' => 15],
            ['product_id' => $coke, 'unit_id' => $shortCan, 'category_id' => $softDrinks, 'stock_quantity' => 200, 'min_stock_level' => 20],
            ['product_id' => $coke, 'unit_id' => $longCan, 'category_id' => $softDrinks, 'stock_quantity' => 120, 'min_stock_level' => 15],
            ['product_id' => $myanmarRed, 'unit_id' => $bottle, 'category_id' => $cigarettes, 'stock_quantity' => 500, 'min_stock_level' => 50],
            ['product_id' => $luckyStrike, 'unit_id' => $bottle, 'category_id' => $cigarettes, 'stock_quantity' => 300, 'min_stock_level' => 30],
            ['product_id' => $lays, 'unit_id' => $pack, 'category_id' => $snacks, 'stock_quantity' => 80, 'min_stock_level' => 10],
            ['product_id' => $evian, 'unit_id' => $bottle, 'category_id' => $water, 'stock_quantity' => 60, 'min_stock_level' => 10],
            ['product_id' => $evian, 'unit_id' => $shortCan, 'category_id' => $water, 'stock_quantity' => 80, 'min_stock_level' => 10],
        ];

        foreach ($variants as $variant) {
            $unitsPerPackage = fake()->numberBetween(6, 48);
            $isBeer = $variant['category_id'] === $beer;
            $sellingPrice = $isBeer
                ? round(fake()->randomFloat(2, 50000, 80000), 2)
                : round(fake()->randomFloat(2, 2000, 20000), 2);
            $costPrice = round($sellingPrice * fake()->randomFloat(2, 0.4, 0.7), 2);
            $perUnitPrice = round($sellingPrice / $unitsPerPackage, 2);

            DB::table('product_variants')->insert(array_merge([
                'product_id' => $variant['product_id'],
                'unit_id' => $variant['unit_id'],
                'stock_quantity' => $variant['stock_quantity'],
                'min_stock_level' => $variant['min_stock_level'],
            ], [
                'units_per_package' => $unitsPerPackage,
                'cost_price' => $costPrice,
                'selling_price' => $sellingPrice,
                'per_unit_price' => $perUnitPrice,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        if (! app()->isLocal()) {
            return;
        }

        DB::table('product_variants')->where('sku', 'TIGER001')->delete();
        DB::table('products')->whereIn('sku', ['TIGER001', 'COKE001', 'MMRED001', 'LSTRIKE001', 'LAYS001', 'EVIAN001'])->delete();
        DB::table('categories')->whereIn('slug', ['beer', 'soft-drinks', 'cigarettes', 'snacks', 'water'])->delete();
        DB::table('units')->whereIn('name', ['ပုလင်း', 'ဗူး', 'ကော်ဗူး', 'သံဗူး (တို)', 'သံဗူး (ရှည်)', 'ထုတ်', 'လုံး', 'အိတ်'])->delete();
    }
};
