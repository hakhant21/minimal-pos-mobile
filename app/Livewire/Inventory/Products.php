<?php

namespace App\Livewire\Inventory;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Products extends Component
{
    public int $perPage = 10;

    public bool $showForm = false;

    public ?int $editingProductId = null;

    public ?int $deletingProductId = null;

    // Product fields
    #[Rule('required|exists:categories,id')]
    public ?int $category_id = null;

    #[Rule('required|max:255')]
    public string $name = '';

    #[Rule('nullable|string|max:255')]
    public ?string $brand = null;

    public bool $is_active = true;

    // Dynamic variants array
    public array $variants = [];

    public string $search = '';

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingProductId = null;
        $this->addVariant();
    }

    public function addVariant(): void
    {
        $this->variants[] = [
            'unit_id' => null,
            'units_per_package' => null,
            'cost_price' => 0,
            'selling_price' => 0,
            'per_unit_price' => null,
            'stock_quantity' => 0,
            'min_stock_level' => 5,
            'max_stock_level' => null,
        ];
    }

    public function removeVariant(int $index): void
    {
        if (count($this->variants) > 1) {
            unset($this->variants[$index]);
            $this->variants = array_values($this->variants);
        }
    }

    public function save(): void
    {
        $this->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'brand' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'variants' => 'required|array|min:1',
            'variants.*.unit_id' => 'required|exists:units,id',
            'variants.*.units_per_package' => 'nullable|integer|min:1',
            'variants.*.cost_price' => 'required|numeric|min:0',
            'variants.*.selling_price' => 'required|numeric|min:0.01',
            'variants.*.per_unit_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.min_stock_level' => 'nullable|integer|min:0',
            'variants.*.max_stock_level' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () {
            $product = Product::create([
                'category_id' => $this->category_id,
                'name' => $this->name,
                'sku' => $this->generateSku(),
                'brand' => $this->brand,
                'is_active' => $this->is_active,
            ]);

            foreach ($this->variants as $variant) {
                $perUnitPrice = $variant['units_per_package']
                    ? ($variant['per_unit_price'] ?? $variant['selling_price'])
                    : $variant['selling_price'];

                $product->variants()->create([
                    'unit_id' => $variant['unit_id'],
                    'units_per_package' => $variant['units_per_package'] ?? null,
                    'cost_price' => $variant['cost_price'],
                    'selling_price' => $variant['selling_price'],
                    'per_unit_price' => $perUnitPrice,
                    'stock_quantity' => $variant['stock_quantity'] ?? 0,
                    'min_stock_level' => $variant['min_stock_level'] ?? 5,
                    'max_stock_level' => $variant['max_stock_level'] ?? null,
                    'is_active' => true,
                ]);
            }
        });

        session()->flash('message', 'Product and variants created successfully.');
        $this->resetForm();
    }

    public function edit(Product $product): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingProductId = $product->id;
        $this->category_id = $product->category_id;
        $this->name = $product->name;
        $this->brand = $product->brand;
        $this->is_active = $product->is_active;

        $this->variants = $product->variants->map(fn ($v) => [
            'id' => $v->id,
            'unit_id' => $v->unit_id,
            'units_per_package' => $v->units_per_package,
            'cost_price' => (float) $v->cost_price,
            'selling_price' => (float) $v->selling_price,
            'per_unit_price' => $v->per_unit_price ? (float) $v->per_unit_price : null,
            'stock_quantity' => $v->stock_quantity,
            'min_stock_level' => $v->min_stock_level,
            'max_stock_level' => $v->max_stock_level,
        ])->toArray();

        if (empty($this->variants)) {
            $this->addVariant();
        }
    }

    public function update(): void
    {
        $this->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'brand' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'variants' => 'required|array|min:1',
            'variants.*.unit_id' => 'required|exists:units,id',
            'variants.*.cost_price' => 'required|numeric|min:0',
            'variants.*.selling_price' => 'required|numeric|min:0.01',
            'variants.*.per_unit_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.min_stock_level' => 'nullable|integer|min:0',
            'variants.*.max_stock_level' => 'nullable|integer|min:0',
        ]);

        $product = Product::findOrFail($this->editingProductId);

        DB::transaction(function () use ($product) {
            $product->update([
                'category_id' => $this->category_id,
                'name' => $this->name,
                'brand' => $this->brand,
                'is_active' => $this->is_active,
            ]);

            $existingIds = [];

            foreach ($this->variants as $variantData) {
                $perUnitPrice = ($variantData['units_per_package'] ?? null)
                    ? ($variantData['per_unit_price'] ?? $variantData['selling_price'])
                    : $variantData['selling_price'];

                if (isset($variantData['id'])) {
                    $existingIds[] = $variantData['id'];
                    $product->variants()->where('id', $variantData['id'])->update([
                        'unit_id' => $variantData['unit_id'],
                        'units_per_package' => $variantData['units_per_package'] ?? null,
                        'cost_price' => $variantData['cost_price'],
                        'selling_price' => $variantData['selling_price'],
                        'per_unit_price' => $perUnitPrice,
                        'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                        'min_stock_level' => $variantData['min_stock_level'] ?? 5,
                        'max_stock_level' => $variantData['max_stock_level'] ?? null,
                    ]);
                } else {
                    $newVariant = $product->variants()->create([
                        'unit_id' => $variantData['unit_id'],
                        'units_per_package' => $variantData['units_per_package'] ?? null,
                        'cost_price' => $variantData['cost_price'],
                        'selling_price' => $variantData['selling_price'],
                        'per_unit_price' => $perUnitPrice,
                        'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                        'min_stock_level' => $variantData['min_stock_level'] ?? 5,
                        'max_stock_level' => $variantData['max_stock_level'] ?? null,
                        'is_active' => true,
                    ]);
                    $existingIds[] = $newVariant->id;
                }
            }

            $product->variants()->whereNotIn('id', $existingIds)->delete();
        });

        session()->flash('message', 'Product updated successfully.');
        $this->resetForm();
    }

    private function generateSku(): string
    {
        $category = Category::find($this->category_id);
        $categoryPrefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $category->name), 0, 3));
        $productPrefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $this->name), 0, 6));
        $baseSku = "{$categoryPrefix}-{$productPrefix}";

        $count = Product::where('sku', 'like', $baseSku.'%')->count();
        $number = str_pad((string) ($count + 1), 3, '0', STR_PAD_LEFT);

        return "{$baseSku}-{$number}";
    }

    public function loadMore(): void
    {
        $this->perPage += 10;
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
        $product = Product::with('variants.saleItems')->findOrFail($this->deletingProductId);

        foreach ($product->variants as $variant) {
            if ($variant->saleItems()->count() > 0) {
                session()->flash('error', 'Cannot delete product with existing sale records.');
                $this->deletingProductId = null;

                return;
            }
        }

        $product->variants()->delete();
        $product->delete();
        $this->deletingProductId = null;
        session()->flash('message', 'Product deleted successfully.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function updatedSearch(): void {}

    private function resetForm(): void
    {
        $this->showForm = false;
        $this->editingProductId = null;
        $this->category_id = null;
        $this->name = '';
        $this->brand = null;
        $this->is_active = true;
        $this->variants = [];
    }

    public function render()
    {
        $productsQuery = Product::with('category', 'variants.unit')->withCount('variants');

        if (! empty($this->search)) {
            $productsQuery->where(function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('sku', 'like', '%'.$this->search.'%')
                    ->orWhereHas('category', function ($q) {
                        $q->where('name', 'like', '%'.$this->search.'%');
                    });
            });
        }

        $totalProducts = $productsQuery->count();

        return view('livewire.inventory.products', [
            'products' => $productsQuery->orderBy('name')->take($this->perPage)->get(),
            'categories' => Category::orderBy('name')->get(),
            'units' => Unit::ordered()->get(),
            'hasMorePages' => $totalProducts > $this->perPage,
            'variants' => $this->variants,
        ]);
    }
}
