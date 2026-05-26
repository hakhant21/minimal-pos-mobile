<?php

namespace App\Models;

use Database\Factories\UnitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    /** @use HasFactory<UnitFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'quantity',
        'price',
        'cost_price',
        'sku',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
        ];
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Unit $unit) {
            if (is_null($unit->sku)) {
                $unit->sku = $unit->generateSku();
            }
        });
    }

    public function generateSku(): string
    {
        $categoryAbbr = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $this->product->category->name), 0, 3));
        $productAbbr = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $this->product->name), 0, 4));
        $unitAbbr = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $this->name), 0, 3));

        $prefix = "{$categoryAbbr}-{$productAbbr}-{$unitAbbr}";

        $lastSequence = static::where('sku', 'like', "{$prefix}-%")
            ->orderByRaw('CAST(SUBSTR(sku, -3) AS INTEGER) DESC')
            ->value('sku');

        $nextNumber = $lastSequence
            ? (int) substr($lastSequence, -3) + 1
            : 1;

        return "{$prefix}-".str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
