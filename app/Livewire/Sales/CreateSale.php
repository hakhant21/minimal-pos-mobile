<?php

namespace App\Livewire\Sales;

use App\Models\Product;
use App\Models\Sale;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CreateSale extends Component
{
    public function boot(): void
    {
        $this->normalizeCartItems();
    }
    #[Rule('required|integer|min:1')]
    public ?int $selectedProductId = null;

    #[Rule('required|integer|min:1')]
    public ?int $selectedUnitId = null;

    #[Rule('required|integer|min:1')]
    public ?int $itemQuantity = 1;

    public array $cart = [];

    public ?string $notes = null;

    public array $units = [];

    public string $productSearch = '';

    public bool $showProductDropdown = false;

    public function mount()
    {
        $this->itemQuantity = 1;
        $this->cart = [];
        $this->showProductDropdown = false;
    }

    public function updatedProductSearch()
    {
        $this->showProductDropdown = true;
    }

    public function selectProduct($productId)
    {
        $this->selectedProductId = (int) $productId;
        $this->selectedUnitId = null;
        $this->showProductDropdown = false;

        // Get product name for search field
        $product = Product::find($this->selectedProductId);
        if ($product) {
            $this->productSearch = $product->name;
        }

        $this->loadProductUnits();
    }

    public function loadProductUnits(): void
    {
        $this->units = [];

        if ($this->selectedProductId) {
            $product = Product::with('units')->find($this->selectedProductId);
            if ($product && $product->units) {
                foreach ($product->units as $unit) {
                    $this->units[] = [
                        'id' => $unit->id,
                        'name' => $unit->name,
                        'price' => (float) ($unit->pivot->price ?? $unit->price ?? 0),
                    ];
                }
            }
        }
    }

    public function incrementQuantity(): void
    {
        $this->itemQuantity++;
    }

    public function decrementQuantity(): void
    {
        if ($this->itemQuantity > 1) {
            $this->itemQuantity--;
        }
    }

    public function addToCart(): void
    {
        $this->validate();

        $product = Product::with('units')->findOrFail($this->selectedProductId);
        $unit = $product->units->firstWhere('id', $this->selectedUnitId);

        if (! $unit) {
            session()->flash('error', 'Selected unit not found for this product.');

            return;
        }

        $unitPrice = (float) ($unit->pivot->price ?? $unit->price ?? 0);
        $subtotal = $unitPrice * $this->itemQuantity;

        // Check if product with same unit already exists in cart
        $existingIndex = null;
        foreach ($this->cart as $index => $item) {
            if ($item['product_id'] == $product->id && $item['unit_id'] == $unit->id) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            // Update existing item
            $this->cart[$existingIndex]['quantity'] += $this->itemQuantity;
            $this->cart[$existingIndex]['subtotal'] = $this->cart[$existingIndex]['unit_price'] * $this->cart[$existingIndex]['quantity'];
        } else {
            // Add new item
            $this->cart[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'unit_id' => $unit->id,
                'unit_name' => $unit->name,
                'quantity' => $this->itemQuantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ];
        }

        $this->reset(['selectedProductId', 'selectedUnitId', 'units', 'productSearch']);
        $this->showProductDropdown = false;
        $this->itemQuantity = 1;

        session()->flash('message', 'Item added to cart successfully!');
    }

    public function increaseCartItemQuantity(int $index): void
    {
        if (isset($this->cart[$index])) {
            $this->cart[$index]['quantity'] = (int) ($this->cart[$index]['quantity'] ?? 0) + 1;
            $this->cart[$index]['unit_price'] = (float) ($this->cart[$index]['unit_price'] ?? 0);
            $this->cart[$index]['subtotal'] = $this->cart[$index]['unit_price'] * $this->cart[$index]['quantity'];
            $this->cart = array_values($this->cart);
        }
    }

    public function decreaseCartItemQuantity(int $index): void
    {
        if (isset($this->cart[$index])) {
            $currentQty = (int) ($this->cart[$index]['quantity'] ?? 0);
            if ($currentQty > 1) {
                $this->cart[$index]['quantity'] = $currentQty - 1;
                $this->cart[$index]['unit_price'] = (float) ($this->cart[$index]['unit_price'] ?? 0);
                $this->cart[$index]['subtotal'] = $this->cart[$index]['unit_price'] * $this->cart[$index]['quantity'];
                $this->cart = array_values($this->cart);
            } else {
                $this->removeFromCart($index);
            }
        }
    }

    public function removeFromCart(int $index): void
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function closeDropdown()
    {
        $this->showProductDropdown = false;
    }

    private function normalizeCartItems(): void
    {
        foreach ($this->cart as $index => $item) {
            $this->cart[$index]['quantity'] = (int) ($item['quantity'] ?? 0);
            $this->cart[$index]['unit_price'] = (float) ($item['unit_price'] ?? 0);
            $this->cart[$index]['subtotal'] = (float) ($item['subtotal'] ?? 0);
            $this->cart[$index]['product_id'] = (int) ($item['product_id'] ?? 0);
            $this->cart[$index]['unit_id'] = (int) ($item['unit_id'] ?? 0);
        }
    }

    public function completeSale(): void
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Add at least one item to the sale.');

            return;
        }

        $total = array_sum(array_column($this->cart, 'subtotal'));

        $sale = Sale::create([
            'total_amount' => $total,
            'notes' => $this->notes,
        ]);

        foreach ($this->cart as $item) {
            $sale->items()->create([
                'product_id' => $item['product_id'],
                'unit_id' => $item['unit_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        $this->reset(['cart', 'notes', 'productSearch']);
        $this->showProductDropdown = false;
        $this->itemQuantity = 1;

        session()->flash('message', 'Sale completed successfully.');
    }

    public function render()
    {
        // Get all products for the dropdown
        $allProducts = Product::orderBy('name')->get();

        // Filter products based on search
        if (! empty($this->productSearch)) {
            $allProducts = $allProducts->filter(function ($product) {
                return stripos($product->name, $this->productSearch) !== false;
            });
        }

        return view('livewire.sales.create-sale', [
            'products' => $allProducts,
            'allProducts' => Product::orderBy('name')->get(), // For debugging
        ]);
    }
}
