<?php

namespace App\Livewire;

use App\Models\ProductVariant;
use App\Models\Sale;
use Livewire\Component;

class Dashboard extends Component
{
    public array $expandedSales = [];

    public function toggleRecentSale(int $saleId): void
    {
        if (in_array($saleId, $this->expandedSales)) {
            $this->expandedSales = array_values(array_diff($this->expandedSales, [$saleId]));
        } else {
            $this->expandedSales[] = $saleId;
        }
    }

    public function render()
    {
        $totalStock = ProductVariant::sum('stock_quantity');
        $todaySales = Sale::whereDate('created_at', today())->count();
        $todayRevenue = Sale::whereDate('created_at', today())->sum('total');

        $inventoryValue = ProductVariant::where('stock_quantity', '>', 0)
            ->selectRaw('SUM(stock_quantity * cost_price) as total_value')
            ->value('total_value') ?? 0;

        $lowStockVariants = ProductVariant::with('product.category')
            ->whereColumn('stock_quantity', '<=', 'min_stock_level')
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity')
            ->get();

        $recentSales = Sale::withCount('items')
            ->with(['items.variant.product', 'items.variant.unit'])
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.dashboard', compact(
            'totalStock',
            'lowStockVariants',
            'todaySales',
            'todayRevenue',
            'recentSales',
            'inventoryValue',
        ));
    }
}
