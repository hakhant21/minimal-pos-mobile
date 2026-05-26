<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->string('sku')->nullable()->unique()->after('product_id');
        });

        $this->backfillSkus();
    }

    private function backfillSkus(): void
    {
        $units = DB::table('units')
            ->join('products', 'units.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereNull('units.sku')
            ->select('units.id', 'categories.name as category_name', 'products.name as product_name', 'units.name as unit_name')
            ->get()
            ->groupBy(fn ($u) => strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $u->category_name), 0, 3))
                .'-'.strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $u->product_name), 0, 4))
                .'-'.strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $u->unit_name), 0, 3)));

        foreach ($units as $prefix => $group) {
            foreach ($group as $i => $unit) {
                $number = str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT);
                DB::table('units')->where('id', $unit->id)->update(['sku' => "{$prefix}-{$number}"]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('sku');
        });
    }
};
