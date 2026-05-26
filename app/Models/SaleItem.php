<?php

namespace App\Models;

use Database\Factories\SaleItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    /** @use HasFactory<SaleItemFactory> */
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'unit_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (SaleItem $item) {
            $item->quantity ??= 0;
            $item->unit_price ??= 0;
            $item->subtotal ??= 0;
            $item->product_id ??= 0;
            $item->unit_id ??= 0;
        });

        static::created(function (SaleItem $item) {
            if ($item->product) {
                $item->product->decrement('stock', ($item->unit?->quantity ?? 1) * $item->quantity);
            }
        });

        static::deleted(function (SaleItem $item) {
            if ($item->product) {
                $item->product->increment('stock', ($item->unit?->quantity ?? 1) * $item->quantity);
            }
        });
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
