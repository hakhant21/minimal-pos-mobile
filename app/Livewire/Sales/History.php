<?php

namespace App\Livewire\Sales;

use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public ?int $expandedSaleId = null;

    public ?int $deleteSaleId = null;

    public function toggleSale(int $saleId): void
    {
        $this->expandedSaleId = $this->expandedSaleId === $saleId ? null : $saleId;
    }

    public function confirmDelete(int $saleId): void
    {
        $this->deleteSaleId = $saleId;
    }

    public function cancelDelete(): void
    {
        $this->deleteSaleId = null;
    }

    public function deleteSale(): void
    {
        $sale = Sale::with('items.variant')->findOrFail($this->deleteSaleId);

        foreach ($sale->items as $item) {
            if ($item->variant) {
                $item->variant->incrementStock($item->quantity, 'Sale deleted', $sale->id);
            }
        }

        $sale->items()->delete();
        $sale->delete();

        $this->deleteSaleId = null;

        session()->flash('message', 'Sale deleted and stock restored.');
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
