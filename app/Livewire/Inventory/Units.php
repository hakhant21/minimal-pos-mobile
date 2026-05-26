<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use App\Models\Unit;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Units extends Component
{
    public bool $showForm = false;

    public ?int $editingUnitId = null;

    public ?int $deletingUnitId = null;

    #[Rule('required|exists:products,id')]
    public ?int $product_id = null;

    public string $productSearch = '';

    #[Rule('required|max:255')]
    public string $name = '';

    #[Rule('required|integer|min:1')]
    public int $quantity = 1;

    #[Rule('required|numeric|min:0')]
    public string $price = '0.00';

    public string $search = '';

    public int $perPage = 10;

    public int $productPerPage = 8;

    public array $expandedProducts = [];

    public function toggleProduct(int $productId): void
    {
        if (in_array($productId, $this->expandedProducts)) {
            $this->expandedProducts = array_diff($this->expandedProducts, [$productId]);
        } else {
            $this->expandedProducts[] = $productId;
        }
    }

    public function loadMore(): void
    {
        $this->perPage += 10;
    }

    public function loadMoreProducts(): void
    {
        $this->productPerPage += 8;
    }

    private function generateSku(): string
    {
        $product = Product::with('category')->find($this->product_id);

        $categoryPrefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $product->category->name), 0, 3));
        $productPrefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $product->name), 0, 4));
        $unitPrefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $this->name), 0, 3));
        $baseSku = "{$categoryPrefix}-{$productPrefix}-{$unitPrefix}";

        $count = Unit::where('product_id', $this->product_id)
            ->where('sku', 'like', $baseSku.'%')
            ->count();
        $number = str_pad((string) ($count + 1), 3, '0', STR_PAD_LEFT);

        return "{$baseSku}-{$number}";
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingUnitId = null;
    }

    public function edit(Unit $unit): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingUnitId = $unit->id;
        $this->product_id = $unit->product_id;
        $this->name = $unit->name;
        $this->quantity = $unit->quantity;
        $this->price = (string) $unit->price;

        $product = Product::find($unit->product_id);
        if ($product) {
            $this->productSearch = $product->name;
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingUnitId) {
            $unit = Unit::findOrFail($this->editingUnitId);
            $unit->update([
                'product_id' => $this->product_id,
                'name' => $this->name,
                'quantity' => $this->quantity,
                'price' => $this->price,
            ]);
            session()->flash('message', 'Unit updated successfully.');
        } else {
            $unit = Unit::create([
                'product_id' => $this->product_id,
                'name' => $this->name,
                'quantity' => $this->quantity,
                'price' => $this->price,
                'sku' => $this->generateSku(),
            ]);
            session()->flash('message', "Unit created successfully. SKU: {$unit->sku}");
        }

        $this->resetForm();
    }

    public function confirmDelete(Unit $unit): void
    {
        $this->deletingUnitId = $unit->id;
    }

    public function cancelDelete(): void
    {
        $this->deletingUnitId = null;
    }

    public function delete(): void
    {
        $unit = Unit::withCount('saleItems')->findOrFail($this->deletingUnitId);

        if ($unit->sale_items_count > 0) {
            session()->flash('error', 'Cannot delete unit with existing sale records.');
            $this->deletingUnitId = null;

            return;
        }

        $unit->delete();
        $this->deletingUnitId = null;
        session()->flash('message', 'Unit deleted successfully.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function selectUnitProduct(int $productId): void
    {
        $this->product_id = $productId;
        $product = Product::find($productId);
        if ($product) {
            $this->productSearch = $product->name;
        }
    }

    public function updatedSearch(): void
    {
        $this->perPage = 10;
        $this->expandedProducts = [];
    }

    public function updatedProductSearch(): void
    {
        $this->productPerPage = 8;
    }

    private function resetForm(): void
    {
        $this->showForm = false;
        $this->editingUnitId = null;
        $this->product_id = null;
        $this->productSearch = '';
        $this->name = '';
        $this->quantity = 1;
        $this->price = '0.00';
    }

    public function render()
    {
        $productsQuery = Product::with('category')->orderBy('name');

        if (! empty($this->productSearch)) {
            $productsQuery->where('name', 'like', '%'.$this->productSearch.'%');
        }

        $products = Product::with(['units' => function ($query) {
            $query->orderBy('name');
        }])->withCount('units');

        if (! empty($this->search)) {
            $products->where(function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhereHas('units', function ($q) {
                        $q->where('name', 'like', '%'.$this->search.'%')
                            ->orWhere('sku', 'like', '%'.$this->search.'%');
                    });
            });
        }

        $totalProducts = $products->count();

        return view('livewire.inventory.units', [
            'products' => $products->orderBy('name')->take($this->perPage)->get(),
            'allProducts' => $productsQuery->take($this->productPerPage)->get(),
            'hasMorePages' => $totalProducts > $this->perPage,
            'hasMoreProducts' => $productsQuery->count() > $this->productPerPage,
        ]);
    }
}
