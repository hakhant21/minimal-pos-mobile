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
        ));
    }
}
