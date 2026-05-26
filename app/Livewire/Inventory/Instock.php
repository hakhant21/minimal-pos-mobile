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
            // Auto-select the first unit if available
            if ($this->product->units->isNotEmpty()) {
                $this->unit_id = $this->product->units->first()->id;
            }
        }
    }

    public function updatedUnitId(): void
    {
        // No need for separate selectedUnit variable, we can get it from product->units
    }

    public function updatedProductId(): void
    {
        $this->product = Product::with('units')->find($this->product_id);
        if ($this->product && $this->product->units->isNotEmpty()) {
            $this->unit_id = $this->product->units->first()->id;
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

        // Add stock logic
        $product->increment('stock', $this->quantity);

        $this->reset(['product_id', 'unit_id', 'quantity', 'product', 'productSearch']);

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
            $productsQuery->where('name', 'like', '%' . $this->productSearch . '%');
        }

        return view('livewire.inventory.instock', [
            'products' => $productsQuery->take($this->perPage)->get(),
            'hasMorePages' => $productsQuery->count() > $this->perPage,
            'lowStockProducts' => Product::with('category')->where('stock', '<', 10)->orderBy('stock')->get(),
            'selectedUnit' => $this->getSelectedUnitProperty(),
        ]);
    }
}
