<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $beerCat = Category::where('slug', 'beer')->first();
        $softDrinksCat = Category::where('slug', 'soft-drinks')->first();
        $candyCat = Category::where('slug', 'candy')->first();
        $snacksCat = Category::where('slug', 'snacks')->first();
        $waterCat = Category::where('slug', 'water')->first();
        $juiceCat = Category::where('slug', 'juice')->first();

        $bottle = Unit::where('name', 'Bottle')->first();
        $can = Unit::where('name', 'Can')->first();
        $piece = Unit::where('name', 'Piece')->first();
        $pack = Unit::where('name', 'Pack')->first();
        $case = Unit::where('name', 'Case')->first();
        $box = Unit::where('name', 'Box')->first();

        // 1. Tiger Beer
        $tiger = Product::create([
            'category_id' => $beerCat->id,
            'name' => 'Tiger Beer',
            'sku' => 'TIGER001',
            'brand' => 'Tiger',
            'description' => "Singapore's favorite beer, crisp and refreshing",
            'is_taxable' => true,
        ]);

        $tigerBottle = ProductVariant::create([
            'product_id' => $tiger->id,
            'unit_id' => $bottle->id,
            'units_per_package' => 1,
            'cost_price' => 1.80,
            'selling_price' => 2.50,
            'stock_quantity' => 100,
            'min_stock_level' => 10,
            'barcode' => 'TIGER001BTL',
            'location' => 'Aisle 1 - Shelf 2',
        ]);

        $tigerCan = ProductVariant::create([
            'product_id' => $tiger->id,
            'unit_id' => $can->id,
            'units_per_package' => 1,
            'cost_price' => 1.50,
            'selling_price' => 2.20,
            'stock_quantity' => 150,
            'min_stock_level' => 15,
            'barcode' => 'TIGER001CAN',
            'location' => 'Aisle 1 - Shelf 3',
        ]);

        $tigerPack = ProductVariant::create([
            'product_id' => $tiger->id,
            'unit_id' => $pack->id,
            'units_per_package' => 12,
            'cost_price' => 20.00,
            'selling_price' => 28.00,
            'stock_quantity' => 20,
            'min_stock_level' => 5,
            'barcode' => 'TIGER001PCK',
            'location' => 'Aisle 1 - Shelf 1',
        ]);


        // 2. Coca-Cola
        $coke = Product::create([
            'category_id' => $softDrinksCat->id,
            'name' => 'Coca-Cola',
            'sku' => 'COKE001',
            'brand' => 'Coca-Cola',
            'description' => 'Classic Coke taste, perfectly refreshing',
            'is_taxable' => true,
        ]);

        $cokeCan = ProductVariant::create([
            'product_id' => $coke->id,
            'unit_id' => $can->id,
            'units_per_package' => 1,
            'cost_price' => 0.80,
            'selling_price' => 1.50,
            'stock_quantity' => 200,
            'min_stock_level' => 20,
            'barcode' => 'COKE001CAN',
            'location' => 'Aisle 2 - Shelf 1',
        ]);

        ProductVariant::create([
            'product_id' => $coke->id,
            'unit_id' => $pack->id,
            'units_per_package' => 6,
            'cost_price' => 4.20,
            'selling_price' => 7.50,
            'stock_quantity' => 30,
            'min_stock_level' => 5,
            'barcode' => 'COKE001PCK',
            'location' => 'Aisle 2 - Shelf 2',
        ]);

        // 3. Mentos
        $mentos = Product::create([
            'category_id' => $candyCat->id,
            'name' => 'Mentos Mint',
            'sku' => 'MENTOS001',
            'brand' => 'Mentos',
            'description' => 'Fresh mint candy in a roll',
            'is_taxable' => false,
        ]);

        $mentosSingle = ProductVariant::create([
            'product_id' => $mentos->id,
            'unit_id' => $piece->id,
            'units_per_package' => 1,
            'cost_price' => 0.30,
            'selling_price' => 0.50,
            'stock_quantity' => 500,
            'min_stock_level' => 50,
            'barcode' => 'MENTOS001PCS',
            'location' => 'Aisle 3 - Shelf 1',
        ]);

        $mentosBox = ProductVariant::create([
            'product_id' => $mentos->id,
            'unit_id' => $box->id,
            'units_per_package' => 20,
            'cost_price' => 5.00,
            'selling_price' => 8.00,
            'stock_quantity' => 25,
            'min_stock_level' => 5,
            'barcode' => 'MENTOS001BX',
            'location' => 'Aisle 3 - Shelf 2',
        ]);


        // 4. Lay's Chips
        $lays = Product::create([
            'category_id' => $snacksCat->id,
            'name' => "Lay's Classic Chips",
            'sku' => 'LAYS001',
            'brand' => "Lay's",
            'description' => 'Classic potato chips, perfectly salted',
            'is_taxable' => true,
        ]);

        ProductVariant::create([
            'product_id' => $lays->id,
            'unit_id' => $pack->id,
            'units_per_package' => 1,
            'cost_price' => 1.00,
            'selling_price' => 1.80,
            'stock_quantity' => 80,
            'min_stock_level' => 10,
            'barcode' => 'LAYS001PCK',
            'location' => 'Aisle 4 - Shelf 1',
        ]);

        // 5. Evian Water
        $evian = Product::create([
            'category_id' => $waterCat->id,
            'name' => 'Evian Water',
            'sku' => 'EVIAN001',
            'brand' => 'Evian',
            'description' => 'Natural spring water from the French Alps',
            'is_taxable' => false,
        ]);

        ProductVariant::create([
            'product_id' => $evian->id,
            'unit_id' => $bottle->id,
            'units_per_package' => 1,
            'cost_price' => 1.20,
            'selling_price' => 2.00,
            'stock_quantity' => 60,
            'min_stock_level' => 10,
            'barcode' => 'EVIAN001BTL',
            'location' => 'Aisle 5 - Shelf 1',
        ]);

        ProductVariant::create([
            'product_id' => $evian->id,
            'unit_id' => $pack->id,
            'units_per_package' => 6,
            'cost_price' => 6.00,
            'selling_price' => 9.50,
            'stock_quantity' => 15,
            'min_stock_level' => 3,
            'barcode' => 'EVIAN001PCK',
            'location' => 'Aisle 5 - Shelf 2',
        ]);

        // 6. Minute Maid Orange Juice
        $minuteMaid = Product::create([
            'category_id' => $juiceCat->id,
            'name' => 'Minute Maid Orange Juice',
            'sku' => 'MM001',
            'brand' => 'Minute Maid',
            'description' => '100% pure orange juice, no added sugar',
            'is_taxable' => false,
        ]);

        ProductVariant::create([
            'product_id' => $minuteMaid->id,
            'unit_id' => $bottle->id,
            'units_per_package' => 1,
            'cost_price' => 1.80,
            'selling_price' => 3.00,
            'stock_quantity' => 40,
            'min_stock_level' => 5,
            'barcode' => 'MM001BTL',
            'location' => 'Aisle 6 - Shelf 1',
        ]);

        ProductVariant::create([
            'product_id' => $minuteMaid->id,
            'unit_id' => $pack->id,
            'units_per_package' => 4,
            'cost_price' => 6.00,
            'selling_price' => 9.00,
            'stock_quantity' => 10,
            'min_stock_level' => 2,
            'barcode' => 'MM001PCK',
            'location' => 'Aisle 6 - Shelf 2',
        ]);
    }
}
