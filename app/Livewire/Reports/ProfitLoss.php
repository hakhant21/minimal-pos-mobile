<?php

namespace App\Livewire\Reports;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProfitLoss extends Component
{
    public string $selectedMonth = '';

    public array $months = [];

    public function mount(): void
    {
        $this->months = Sale::selectRaw("strftime('%Y-%m', created_at) as month")
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->pluck('month')
            ->toArray();

        $this->selectedMonth = $this->months[0] ?? date('Y-m');
    }

    public function render()
    {
        $summary = [
            'total_revenue' => 0,
            'total_cogs' => 0,
            'total_profit' => 0,
            'margin' => 0,
        ];

        $productRows = [];

        if ($this->selectedMonth) {
            $rows = DB::table('sale_items')
                ->join('product_variants', 'product_variants.id', '=', 'sale_items.product_variant_id')
                ->join('products', 'products.id', '=', 'product_variants.product_id')
                ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
                ->whereRaw("strftime('%Y-%m', sales.created_at) = ?", [$this->selectedMonth])
                ->select(
                    'products.id',
                    'products.name',
                    DB::raw('COUNT(DISTINCT sale_items.id) as items_sold'),
                    DB::raw('SUM(sale_items.quantity) as total_qty'),
                    DB::raw('SUM(sale_items.total_price) as revenue'),
                    DB::raw('SUM(sale_items.quantity * COALESCE(sale_items.cost_price, product_variants.cost_price, 0)) as cogs'),
                )
                ->groupBy('products.id', 'products.name')
                ->orderBy('revenue', 'desc')
                ->get();

            foreach ($rows as $row) {
                $profit = $row->revenue - $row->cogs;
                $margin = $row->revenue > 0 ? round(($profit / $row->revenue) * 100, 1) : 0;

                $productRows[] = [
                    'id' => $row->id,
                    'name' => $row->name,
                    'items_sold' => $row->items_sold,
                    'total_qty' => $row->total_qty,
                    'revenue' => (float) $row->revenue,
                    'cogs' => (float) $row->cogs,
                    'profit' => $profit,
                    'margin' => $margin,
                ];

                $summary['total_revenue'] += (float) $row->revenue;
                $summary['total_cogs'] += (float) $row->cogs;
            }

            $summary['total_profit'] = $summary['total_revenue'] - $summary['total_cogs'];
            $summary['margin'] = $summary['total_revenue'] > 0
                ? round(($summary['total_profit'] / $summary['total_revenue']) * 100, 1)
                : 0;
        }

        $allMonths = $this->months;
        if (! in_array(date('Y-m'), $allMonths)) {
            $allMonths[] = date('Y-m');
        }
        $allMonths = array_values(array_unique(array_reverse($allMonths)));

        return view('livewire.reports.profit-loss', [
            'productRows' => $productRows,
            'summary' => $summary,
            'allMonths' => $allMonths,
        ]);
    }
}
