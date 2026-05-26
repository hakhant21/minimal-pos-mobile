<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Sale;
use Livewire\Component;

class Dashboard extends Component
{
    public $expandedSales = [];

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
        $totalStock = Product::sum('stock');
        $todaySales = Sale::whereDate('created_at', today())->count();
        $todayRevenue = Sale::whereDate('created_at', today())->sum('total_amount');

        $inventoryValue = Product::join('units', 'products.id', '=', 'units.product_id')
            ->whereNotNull('units.cost_price')
            ->where('units.cost_price', '>', 0)
            ->selectRaw('products.id, products.stock, MIN(units.cost_price / NULLIF(units.quantity, 0)) as min_cost_per_item')
            ->groupBy('products.id', 'products.stock')
            ->get()
            ->sum(fn ($item) => $item->stock * $item->min_cost_per_item);

        $lowStockProducts = Product::with('category')
            ->where('stock', '<', 10)
            ->orderBy('stock')
            ->get();

        $recentSales = Sale::withCount('items')
            ->with(['items.product', 'items.unit'])
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.dashboard', compact(
            'totalStock',
            'lowStockProducts',
            'todaySales',
            'todayRevenue',
            'recentSales',
            'inventoryValue',
        ));
    }
}
