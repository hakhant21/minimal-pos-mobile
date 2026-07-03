<?php

namespace App\Livewire\Sales;

use App\Models\Product;
use App\Models\ProductVariant;
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
    public ?int $selectedVariantId = null;

    #[Rule('required|integer|min:1')]
    public ?int $itemQuantity = 1;

    public array $cart = [];

    public ?string $customerName = null;

    public ?string $notes = null;

    public string $paymentMethod = 'cash';

    public array $variants = [];

    public string $productSearch = '';

    public bool $showProductDropdown = false;

    public function mount(): void
    {
        $this->itemQuantity = 1;
        $this->cart = [];
        $this->customerName = null;
        $this->showProductDropdown = false;
        $this->paymentMethod = 'cash';
    }

    public function updatedProductSearch(): void
    {
        $this->showProductDropdown = true;
    }

    public function updatedSelectedProductId(): void
    {
        $this->selectedVariantId = null;
        $this->loadProductVariants();
    }

    public function selectProduct(int $productId): void
    {
        $this->selectedProductId = $productId;
        $this->selectedVariantId = null;
        $this->showProductDropdown = false;

        $product = Product::find($this->selectedProductId);
        if ($product) {
            $this->productSearch = $product->name;
        }

        $this->loadProductVariants();
    }

    public function loadProductVariants(): void
    {
        $this->variants = [];

        if ($this->selectedProductId) {
            $variants = ProductVariant::with('unit')
                ->where('product_id', $this->selectedProductId)
                ->where('is_active', true)
                ->get();

            foreach ($variants as $variant) {
                $this->variants[] = [
                    'id' => $variant->id,
                    'display_name' => $variant->display_name,
                    'unit_name' => $variant->unit->name,
                    'units_per_package' => $variant->units_per_package,
                    'price' => (float) $variant->current_price,
                    'stock' => $variant->stock_quantity,
                    'stock_status' => $variant->stock_status,
                ];
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

        $variant = ProductVariant::with('product', 'unit')->findOrFail($this->selectedVariantId);

        $unitPrice = (float) $variant->current_price;
        $totalPrice = $unitPrice * $this->itemQuantity;

        $existingIndex = null;
        foreach ($this->cart as $index => $item) {
            if ($item['variant_id'] === $variant->id) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            $this->cart[$existingIndex]['quantity'] += $this->itemQuantity;
            $this->cart[$existingIndex]['total_price'] = $this->cart[$existingIndex]['unit_price'] * $this->cart[$existingIndex]['quantity'];
        } else {
            $this->cart[] = [
                'variant_id' => $variant->id,
                'product_id' => $variant->product_id,
                'product_name' => $variant->product->name,
                'variant_name' => $variant->display_name,
                'unit_name' => $variant->unit->name,
                'unit_price' => $unitPrice,
                'quantity' => $this->itemQuantity,
                'total_price' => $totalPrice,
            ];
        }

        $this->reset(['selectedProductId', 'selectedVariantId', 'variants', 'productSearch']);
        $this->showProductDropdown = false;
        $this->itemQuantity = 1;

        session()->flash('message', 'Item added to cart successfully!');
    }

    public function increaseCartItemQuantity(int $index): void
    {
        if (isset($this->cart[$index])) {
            $this->cart[$index]['quantity']++;
            $this->cart[$index]['total_price'] = $this->cart[$index]['unit_price'] * $this->cart[$index]['quantity'];
            $this->cart = array_values($this->cart);
        }
    }

    public function decreaseCartItemQuantity(int $index): void
    {
        if (isset($this->cart[$index])) {
            if ($this->cart[$index]['quantity'] > 1) {
                $this->cart[$index]['quantity']--;
                $this->cart[$index]['total_price'] = $this->cart[$index]['unit_price'] * $this->cart[$index]['quantity'];
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

    public function closeDropdown(): void
    {
        $this->showProductDropdown = false;
    }

    private function normalizeCartItems(): void
    {
        foreach ($this->cart as $index => $item) {
            $this->cart[$index]['quantity'] = (int) ($item['quantity'] ?? 0);
            $this->cart[$index]['unit_price'] = (float) ($item['unit_price'] ?? 0);
            $this->cart[$index]['total_price'] = (float) ($item['total_price'] ?? 0);
            $this->cart[$index]['product_name'] = (string) ($item['product_name'] ?? '');
            $this->cart[$index]['variant_name'] = (string) ($item['variant_name'] ?? '');
            $this->cart[$index]['unit_name'] = (string) ($item['unit_name'] ?? '');
            $this->cart[$index]['variant_id'] = (int) ($item['variant_id'] ?? 0);
            $this->cart[$index]['product_id'] = (int) ($item['product_id'] ?? 0);
        }
    }

    public function completeSale(): void
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Add at least one item to the sale.');

            return;
        }

        $subtotal = array_sum(array_column($this->cart, 'total_price'));
        $discount = 0;
        $tax = 0;
        $total = $subtotal - $discount + $tax;

        $sale = Sale::create([
            'invoice_number' => Sale::generateInvoiceNumber(),
            'user_id' => auth()->id() ?? 1,
            'customer_name' => $this->customerName,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => $this->paymentMethod,
            'amount_paid' => $total,
            'change_amount' => 0,
            'notes' => $this->notes,
            'completed_at' => now(),
        ]);

        foreach ($this->cart as $item) {
            $sale->items()->create([
                'product_variant_id' => $item['variant_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount' => 0,
                'total_price' => $item['total_price'],
                'tax_amount' => 0,
            ]);

            // Decrement stock and log movement
            $variant = ProductVariant::find($item['variant_id']);
            if ($variant) {
                $variant->decrementStock($item['quantity'], 'Sale completed', $sale->id);
            }
        }

        $this->reset(['cart', 'customerName', 'notes', 'productSearch']);
        $this->showProductDropdown = false;
        $this->itemQuantity = 1;

        session()->flash('message', "Sale completed successfully. Invoice: {$sale->invoice_number}");
    }

    public function render()
    {
        $allProducts = Product::with('variants.unit')
            ->orderBy('name')
            ->get();

        if (! empty($this->productSearch)) {
            $allProducts = $allProducts->filter(function ($product) {
                return stripos($product->name, $this->productSearch) !== false
                    || stripos($product->brand ?? '', $this->productSearch) !== false;
            });
        }

        return view('livewire.sales.create-sale', [
            'products' => $allProducts,
            'cartTotal' => array_sum(array_column($this->cart, 'total_price')),
        ]);
    }
}
