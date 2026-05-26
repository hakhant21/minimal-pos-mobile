<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use App\Models\Unit;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Instock extends Component
{
    #[Rule('required|exists:products,id')]
    public ?int $product_id = null;

    #[Rule('required|exists:units,id')]
    public ?int $unit_id = null;

    #[Rule('required|integer|min:1')]
    public ?int $quantity = null;

    public ?float $purchaseCost = null;

    public int $perPage = 10;

    public ?Product $product = null;

    public string $productSearch = '';

    public function loadMore(): void
    {
        $this->perPage += 10;
    }

    public function selectProduct(int $productId): void
    {
        $this->product_id = $productId;
        $this->product = Product::with('units')->find($productId);
        $this->unit_id = null;

        if ($this->product) {
            $this->productSearch = $this->product->name;
            if ($this->product->units->isNotEmpty()) {
                $firstUnit = $this->product->units->first();
                $this->unit_id = $firstUnit->id;
                $this->purchaseCost = $firstUnit->cost_price;
            }
        }
    }

    public function updatedUnitId(): void
    {
        if ($this->product && $this->unit_id) {
            $unit = $this->product->units->find($this->unit_id);
            if ($unit) {
                $this->purchaseCost = $unit->cost_price;
            }
        }
    }

    public function updatedProductId(): void
    {
        $this->product = Product::with('units')->find($this->product_id);
        if ($this->product && $this->product->units->isNotEmpty()) {
            $firstUnit = $this->product->units->first();
            $this->unit_id = $firstUnit->id;
            $this->purchaseCost = $firstUnit->cost_price;
        }
    }

    public function updatedProductSearch(): void
    {
        $this->resetPage();
    }

    public function addStock(): void
    {
        $this->validate();

        $product = Product::findOrFail($this->product_id);
        $unit = Unit::findOrFail($this->unit_id);

        $product->increment('stock', $this->quantity);

        if ($this->purchaseCost > 0) {
            if ($unit->cost_price) {
                $totalQty = $unit->quantity + $this->quantity;
                $totalCost = ($unit->cost_price * $unit->quantity) + ($this->purchaseCost * $this->quantity);
                $unit->update(['cost_price' => round($totalCost / $totalQty, 2)]);
            } else {
                $unit->update(['cost_price' => $this->purchaseCost]);
            }
        }

        $this->reset(['product_id', 'unit_id', 'quantity', 'product', 'productSearch', 'purchaseCost']);

        session()->flash('message', "Added {$this->quantity} {$unit->name}(s) to {$product->name} stock successfully.");
    }

    public function getSelectedUnitProperty()
    {
        if ($this->unit_id && $this->product) {
            return $this->product->units->find($this->unit_id);
        }

        return null;
    }

    public function render()
    {
        // Build query for paginated products
        $productsQuery = Product::with('units', 'category')->orderBy('name');

        if (! empty($this->productSearch)) {
            $productsQuery->where('name', 'like', '%'.$this->productSearch.'%');
        }

        return view('livewire.inventory.instock', [
            'products' => $productsQuery->take($this->perPage)->get(),
            'hasMorePages' => $productsQuery->count() > $this->perPage,
            'lowStockProducts' => Product::with('category')->where('stock', '<', 10)->orderBy('stock')->get(),
            'selectedUnit' => $this->getSelectedUnitProperty(),
        ]);
    }
}
