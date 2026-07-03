<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Instock extends Component
{
    #[Rule('required|exists:products,id')]
    public ?int $product_id = null;

    #[Rule('required|exists:product_variants,id')]
    public ?int $variant_id = null;

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
        $this->product = Product::with('variants.unit')->find($productId);
        $this->variant_id = null;
        $this->purchaseCost = null;

        if ($this->product) {
            $this->productSearch = $this->product->name;
            $firstVariant = $this->product->variants->first();
            if ($firstVariant) {
                $this->variant_id = $firstVariant->id;
                $this->purchaseCost = (float) $firstVariant->cost_price;
            }
        }
    }

    public function updatedVariantId(): void
    {
        if ($this->product && $this->variant_id) {
            $variant = $this->product->variants->find($this->variant_id);
            if ($variant) {
                $this->purchaseCost = (float) $variant->cost_price;
            }
        }
    }

    public function addStock(): void
    {
        $this->validate();

        $variant = ProductVariant::findOrFail($this->variant_id);

        $variant->increment('stock_quantity', $this->quantity);

        // Update weighted average cost price
        if ($this->purchaseCost > 0) {
            $totalQty = $before + $this->quantity;
            $totalCost = ((float) $variant->cost_price * $before) + ($this->purchaseCost * $this->quantity);
            $variant->update(['cost_price' => round($totalCost / $totalQty, 2)]);
        }

        $added = $this->quantity;
        $this->reset(['product_id', 'variant_id', 'quantity', 'product', 'productSearch', 'purchaseCost']);

        session()->flash('message', "Added {$added} item(s) to stock successfully.");
    }

    public function render()
    {
        $productsQuery = Product::with('variants.unit', 'category')->orderBy('name');

        if (! empty($this->productSearch)) {
            $productsQuery->where('name', 'like', '%' . $this->productSearch . '%');
        }

        $lowStockVariants = ProductVariant::with('product.category', 'unit')
            ->whereColumn('stock_quantity', '<=', 'min_stock_level')
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity')
            ->get();

        return view('livewire.inventory.instock', [
            'products' => $productsQuery->take($this->perPage)->get(),
            'hasMorePages' => $productsQuery->count() > $this->perPage,
            'lowStockVariants' => $lowStockVariants,
            'selectedVariant' => $this->product && $this->variant_id
                ? $this->product->variants->find($this->variant_id)
                : null,
        ]);
    }
}
