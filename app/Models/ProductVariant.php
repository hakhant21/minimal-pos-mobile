<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    /** @use HasFactory<ProductVariantFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'unit_id',
        'units_per_package',
        'cost_price',
        'selling_price',
        'per_unit_price',
        'stock_quantity',
        'min_stock_level',
        'max_stock_level',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'per_unit_price' => 'decimal:2',
            'units_per_package' => 'integer',
            'stock_quantity' => 'integer',
            'min_stock_level' => 'integer',
            'max_stock_level' => 'integer',
        ];
    }

    protected $appends = [
        'display_name',
        'stock_status',
        'stock_status_label',
        'stock_status_color',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function getDisplayNameAttribute(): string
    {
        $unitName = $this->units_per_package
            ? "{$this->units_per_package} {$this->unit->name}"
            : $this->unit->name;

        return "{$this->product->name} - {$unitName}";
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock_quantity <= 0) {
            return 'out_of_stock';
        }

        if ($this->stock_quantity <= $this->min_stock_level) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    public function getStockStatusLabelAttribute(): string
    {
        return [
            'out_of_stock' => 'Out of Stock',
            'low_stock' => 'Low Stock',
            'in_stock' => 'In Stock',
        ][$this->stock_status] ?? 'Unknown';
    }

    public function getStockStatusColorAttribute(): string
    {
        return [
            'out_of_stock' => 'danger',
            'low_stock' => 'warning',
            'in_stock' => 'success',
        ][$this->stock_status] ?? 'secondary';
    }

    public function decrementStock(int $quantity, ?string $reason = null, ?int $saleId = null): static
    {
        $this->decrement('stock_quantity', $quantity);

        return $this;
    }

    public function incrementStock(int $quantity, ?string $reason = null, ?int $saleId = null): static
    {
        $this->increment('stock_quantity', $quantity);

        return $this;
    }

    public function adjustStock(int $quantity, ?string $reason = null): static
    {
        $this->update(['stock_quantity' => $quantity]);

        return $this;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'min_stock_level')
            ->where('stock_quantity', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->whereHas('product', function ($productQuery) use ($search) {
                $productQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        });
    }
}
