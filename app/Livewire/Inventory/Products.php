<?php

namespace App\Livewire\Inventory;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Products extends Component
{
    public int $perPage = 10;

    public bool $showForm = false;

    public ?int $editingProductId = null;

    public ?int $deletingProductId = null;

    public ?int $createdProductId = null;

    public array $createdProductVariants = [];

    // Product fields
    #[Rule('required|exists:categories,id')]
    public ?int $category_id = null;

    #[Rule('required|max:255')]
    public string $name = '';

    #[Rule('nullable|string|max:1000')]
    public ?string $description = null;

    #[Rule('nullable|string|max:255')]
    public ?string $brand = null;

    public bool $is_taxable = true;

    #[Rule('nullable|string|max:255')]
    public ?string $barcode = null;

    #[Rule('nullable|string|max:255')]
    public ?string $image_url = null;

    #[Rule('nullable|numeric|min:0')]
    public ?float $weight = null;

    public bool $is_active = true;

    // Variant fields
    #[Rule('required|exists:units,id')]
    public ?int $variantUnitId = null;

    #[Rule('nullable|integer|min:1')]
    public ?int $variantUnitsPerPackage = null;

    #[Rule('required|numeric|min:0')]
    public float $variantSellingPrice = 0;

    #[Rule('required|numeric|min:0')]
    public float $variantCostPrice = 0;

    #[Rule('nullable|integer|min:0')]
    public ?int $variantStockQuantity = 0;

    #[Rule('nullable|integer|min:0')]
    public ?int $variantMinStockLevel = 5;

    #[Rule('nullable|numeric|min:0')]
    public ?float $variantPackageWeight = null;

    #[Rule('nullable|numeric|min:0')]
    public ?float $variantPromoPrice = null;

    public ?string $variantPromoStart = null;

    public ?string $variantPromoEnd = null;

    #[Rule('nullable|integer|min:0')]
    public ?int $variantMaxStockLevel = null;

    #[Rule('nullable|string|max:255')]
    public ?string $variantLocation = null;

    #[Rule('nullable|string|max:255')]
    public ?string $variantBarcode = null;

    #[Rule('nullable|string|max:255')]
    public ?string $variantImageUrl = null;

    public bool $variantIsActive = true;

    public string $search = '';

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
        $this->brand = $product->brand;
        $this->is_taxable = $product->is_taxable;
        $this->barcode = $product->barcode;
        $this->image_url = $product->image_url;
        $this->weight = $product->weight;
        $this->is_active = $product->is_active;
    }

    public function save(): void
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'description' => 'nullable|string|max:1000',
            'brand' => 'nullable|string|max:255',
            'is_taxable' => 'boolean',
            'barcode' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ];

        // Variant-specific validation is handled in `saveVariant()`.

        $this->validate($rules);

        $data = [
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'brand' => $this->brand,
            'is_taxable' => $this->is_taxable,
            'barcode' => $this->barcode,
            'image_url' => $this->image_url,
            'weight' => $this->weight,
            'is_active' => $this->is_active,
        ];

        if ($this->editingProductId) {
            $product = Product::findOrFail($this->editingProductId);
            $product->update($data);

            session()->flash('message', 'Product updated successfully.');
            $this->resetForm();
        } else {
            $product = Product::create([
                ...$data,
                'sku' => $this->generateSku(),
            ]);

            $this->createdProductId = $product->id;
            $this->createdProductVariants = [];
            $this->variantUnitId = null;
            $this->variantUnitsPerPackage = null;
            $this->variantSellingPrice = 0;
            $this->variantCostPrice = 0;
            $this->variantStockQuantity = 0;
            $this->variantMinStockLevel = 5;

            session()->flash('message', 'Product created. Now add variants below.');
        }

        $this->resetPage();
    }

    public function saveVariant(): void
    {
        $this->validate([
            'variantUnitId' => 'required|exists:units,id',
            'variantUnitsPerPackage' => 'nullable|integer|min:1',
            'variantSellingPrice' => 'required|numeric|min:0',
            'variantCostPrice' => 'required|numeric|min:0',
            'variantStockQuantity' => 'nullable|integer|min:0',
            'variantMinStockLevel' => 'nullable|integer|min:0',
            'variantPackageWeight' => 'nullable|numeric|min:0',
            'variantPromoPrice' => 'nullable|numeric|min:0',
            'variantPromoStart' => 'nullable|date',
            'variantPromoEnd' => 'nullable|date',
            'variantMaxStockLevel' => 'nullable|integer|min:0',
            'variantLocation' => 'nullable|string|max:255',
            'variantBarcode' => 'nullable|string|max:255',
            'variantImageUrl' => 'nullable|string|max:255',
            'variantIsActive' => 'boolean',
        ]);

        $product = Product::findOrFail($this->createdProductId);

        $product->variants()->create([
            'unit_id' => $this->variantUnitId,
            'units_per_package' => $this->variantUnitsPerPackage,
            'package_weight' => $this->variantPackageWeight,
            'cost_price' => $this->variantCostPrice,
            'selling_price' => $this->variantSellingPrice,
            'promo_price' => $this->variantPromoPrice,
            'promo_start' => $this->variantPromoStart,
            'promo_end' => $this->variantPromoEnd,
            'stock_quantity' => $this->variantStockQuantity ?? 0,
            'min_stock_level' => $this->variantMinStockLevel ?? 5,
            'max_stock_level' => $this->variantMaxStockLevel,
            'location' => $this->variantLocation,
            'barcode' => $this->variantBarcode,
            'image_url' => $this->variantImageUrl,
            'is_active' => $this->variantIsActive,
        ]);

        $this->createdProductVariants = $product->variants()
            ->with('unit')
            ->get()
            ->toArray();

        $this->variantUnitId = null;
        $this->variantUnitsPerPackage = null;
        $this->variantSellingPrice = 0;
        $this->variantCostPrice = 0;
        $this->variantStockQuantity = 0;
        $this->variantMinStockLevel = 5;

        session()->flash('message', 'Variant added successfully.');
    }

    public function finish(): void
    {
        $this->resetForm();
        $this->resetPage();
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
        $this->createdProductId = null;
        $this->createdProductVariants = [];
        $this->category_id = null;
        $this->name = '';
        $this->description = null;
        $this->brand = null;
        $this->is_taxable = true;
        $this->variantUnitId = null;
        $this->variantUnitsPerPackage = null;
        $this->variantSellingPrice = 0;
        $this->variantCostPrice = 0;
        $this->variantStockQuantity = 0;
        $this->variantMinStockLevel = 5;
        $this->barcode = null;
        $this->image_url = null;
        $this->weight = null;
        $this->is_active = true;
    }

    public function render()
    {
        $productsQuery = Product::with('category', 'variants.unit')->withCount('variants');

        if (! empty($this->search)) {
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
            'units' => Unit::sellable()->ordered()->get(),
            'hasMorePages' => $totalProducts > $this->perPage,
        ]);
    }
}
