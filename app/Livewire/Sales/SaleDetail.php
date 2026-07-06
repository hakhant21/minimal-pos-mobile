<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;

class SaleDetail extends Component
{
    public Sale $sale;

    public function mount(Sale $sale): void
    {
        $this->sale = $sale->load(['items.variant.product', 'items.variant.unit']);
    }

    public function render()
    {
        return view('livewire.sales.sale-detail', [
            'sale' => $this->sale,
        ]);
    }
}
