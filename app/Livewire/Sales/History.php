<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public ?int $expandedSaleId = null;

    public function toggleSale(int $saleId): void
    {
        $this->expandedSaleId = $this->expandedSaleId === $saleId ? null : $saleId;
    }

    public function render()
    {
        return view('livewire.sales.history', [
            'sales' => Sale::with('items.variant.product', 'items.variant.unit')
                ->latest()
                ->paginate(15),
        ]);
    }
}
