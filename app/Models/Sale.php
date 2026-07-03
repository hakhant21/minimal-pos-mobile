<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    /** @use HasFactory<SaleFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_name',
        'subtotal',
        'discount',
        'tax',
        'total',
        'payment_method',
        'payment_reference',
        'amount_paid',
        'change_amount',
        'notes',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'change_amount' => 'decimal:2',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function variants()
    {
        return $this->hasManyThrough(ProductVariant::class, SaleItem::class);
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeDateRange($query, string $from, string $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $last = static::whereDate('created_at', today())->count() + 1;

        return "{$prefix}-{$date}-" . str_pad((string) $last, 4, '0', STR_PAD_LEFT);
    }

    public static function getDailyTotal(?string $date = null): float
    {
        $date = $date ?? today()->toDateString();

        return (float) static::whereDate('created_at', $date)
            ->completed()
            ->sum('total');
    }

    public static function getMonthlyTotal(?int $month = null, ?int $year = null): float
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        return (float) static::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->completed()
            ->sum('total');
    }
}
