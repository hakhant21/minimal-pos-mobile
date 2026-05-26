<?php

namespace App\Livewire\Inventory;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public int $perPage = 10;

    public bool $showForm = false;

    public ?int $editingProductId = null;

    public ?int $deletingProductId = null;

    #[Rule('required|exists:categories,id')]
    public ?int $category_id = null;

    #[Rule('required|max:255')]
    public string $name = '';

    #[Rule('nullable|string|max:1000')]
    public ?string $description = null;

    #[Rule('required|integer|min:0')]
    public int $stock = 0;

    public string $search = '';

    // Units management
    public array $units = [];

    public $editingUnitIndex = null; // Changed from ?int to allow null

    #[Rule('required|string|max:255')]
    public string $unitName = '';

    #[Rule('required|integer|min:1')]
    public int $unitQuantity = 1;

    #[Rule('required|numeric|min:0')]
    public float $unitPrice = 0;

    public function mount()
    {
        $this->units = [];
        $this->editingUnitIndex = null;
        $this->unitName = '';
        $this->unitQuantity = 1;
        $this->unitPrice = 0;
    }

    public function resetUnitForm(): void
    {
        $this->unitName = '';
        $this->unitQuantity = 1;
        $this->unitPrice = 0;
    }

    public function addUnit(): void
    {
        $this->validate([
            'unitName' => 'required|string|max:255',
            'unitQuantity' => 'required|integer|min:1',
            'unitPrice' => 'required|numeric|min:0',
        ]);

        if ($this->editingUnitIndex !== null) {
            // Update existing unit
            $this->units[$this->editingUnitIndex] = [
                'name' => $this->unitName,
                'quantity' => $this->unitQuantity,
                'price' => $this->unitPrice,
            ];
            $this->editingUnitIndex = null;
        } else {
            // Add new unit
            $this->units[] = [
                'name' => $this->unitName,
                'quantity' => $this->unitQuantity,
                'price' => $this->unitPrice,
            ];
        }

        $this->resetUnitForm();
    }

    public function editUnit($index): void
    {
        if (isset($this->units[$index])) {
            $unit = $this->units[$index];
            $this->unitName = $unit['name'];
            $this->unitQuantity = $unit['quantity'];
            $this->unitPrice = $unit['price'];
            $this->editingUnitIndex = $index;
        }
    }

    public function removeUnit($index): void
    {
        unset($this->units[$index]);
        $this->units = array_values($this->units);

        if ($this->editingUnitIndex === $index) {
            $this->editingUnitIndex = null;
            $this->resetUnitForm();
        } elseif ($this->editingUnitIndex !== null && $this->editingUnitIndex > $index) {
            $this->editingUnitIndex--;
        }
    }

    public function cancelEditUnit(): void
    {
        $this->editingUnitIndex = null;
        $this->resetUnitForm();
    }

    public function loadMore(): void
    {
        $this->perPage += 10;
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingProductId = null;
    }

    public function edit(Product $product): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingProductId = $product->id;
        $this->category_id = $product->category_id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->stock = $product->stock;

        // Load existing units
        $this->units = $product->units->map(function ($unit) {
            return [
                'name' => $unit->name,
                'quantity' => $unit->quantity,
                'price' => $unit->price,
            ];
        })->toArray();

        $this->editingUnitIndex = null;
        $this->resetUnitForm();
    }

    private function generateSku(): string
    {
        $category = Category::find($this->category_id);

        $categoryPrefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $category->name), 0, 3));
        $productPrefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $this->name), 0, 6));
        $baseSku = "{$categoryPrefix}-{$productPrefix}";

        $count = Product::where('sku', 'like', $baseSku . '%')->count();
        $number = str_pad((string) ($count + 1), 3, '0', STR_PAD_LEFT);

        return "{$baseSku}-{$number}";
    }

    private function generateUnitSku(Product $product, string $unitName, int $index): string
    {
        $productSku = $product->sku;
        $unitPrefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $unitName), 0, 4));
        return "{$productSku}-{$unitPrefix}-" . str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT);
    }

    public function save(): void
    {
        $this->validate();

        // Validate that at least one unit is added
        if (empty($this->units)) {
            session()->flash('error', 'Please add at least one unit for this product.');
            return;
        }

        if ($this->editingProductId) {
            $product = Product::findOrFail($this->editingProductId);
            $product->update([
                'category_id' => $this->category_id,
                'name' => $this->name,
                'description' => $this->description,
                'stock' => $this->stock,
            ]);

            // Update units - delete old ones and create new
            $product->units()->delete();
            $this->saveUnits($product);

            session()->flash('message', 'Product updated successfully.');
        } else {
            $product = Product::create([
                'category_id' => $this->category_id,
                'name' => $this->name,
                'description' => $this->description,
                'stock' => $this->stock,
                'sku' => $this->generateSku(),
            ]);

            $this->saveUnits($product);

            session()->flash('message', "Product created successfully. SKU: {$product->sku}");
        }

        $this->resetForm();
        $this->resetPage();
    }

    private function saveUnits(Product $product): void
    {
        foreach ($this->units as $index => $unitData) {
            $product->units()->create([
                'name' => $unitData['name'],
                'quantity' => $unitData['quantity'],
                'price' => $unitData['price'],
                'sku' => $this->generateUnitSku($product, $unitData['name'], $index),
            ]);
        }
    }

    public function confirmDelete(Product $product): void
    {
        $this->deletingProductId = $product->id;
    }

    public function cancelDelete(): void
    {
        $this->deletingProductId = null;
    }

    public function delete(): void
    {
        $product = Product::withCount('units', 'saleItems')->findOrFail($this->deletingProductId);

        if ($product->sale_items_count > 0) {
            session()->flash('error', 'Cannot delete product with existing sale records.');
            $this->deletingProductId = null;
            return;
        }

        if ($product->units_count > 0) {
            $product->units()->delete();
        }

        $product->delete();
        $this->deletingProductId = null;
        session()->flash('message', 'Product deleted successfully.');
        $this->resetPage();
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->showForm = false;
        $this->editingProductId = null;
        $this->category_id = null;
        $this->name = '';
        $this->description = null;
        $this->stock = 0;
        $this->units = [];
        $this->editingUnitIndex = null;
        $this->unitName = '';
        $this->unitQuantity = 1;
        $this->unitPrice = 0;
    }

    public function render()
    {
        $productsQuery = Product::with('category', 'units')->withCount('units');

        if (!empty($this->search)) {
            $productsQuery->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('category', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $totalProducts = $productsQuery->count();

        return view('livewire.inventory.products', [
            'products' => $productsQuery->orderBy('name')->take($this->perPage)->get(),
            'categories' => Category::orderBy('name')->get(),
            'hasMorePages' => $totalProducts > $this->perPage,
            'editingUnitIndex' => $this->editingUnitIndex,
        ]);
    }
}
