<?php

use App\Livewire\Sales\CreateSale;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $category = Category::factory()->create(['name' => 'Beverages']);

    $this->bottleUnit = Unit::factory()->bottle()->create();
    $this->packUnit = Unit::factory()->pack()->create();

    $this->product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Cola',
    ]);

    $this->variantBottle = ProductVariant::factory()->create([
        'product_id' => $this->product->id,
        'unit_id' => $this->bottleUnit->id,
        'units_per_package' => 1,
        'selling_price' => 2.50,
        'stock_quantity' => 100,
    ]);

    $this->variantPack = ProductVariant::factory()->create([
        'product_id' => $this->product->id,
        'unit_id' => $this->packUnit->id,
        'units_per_package' => 12,
        'selling_price' => 24.00,
        'stock_quantity' => 20,
    ]);
});

it('renders the component with products and header', function () {
    Livewire::test(CreateSale::class)
        ->assertSee('New Sale')
        ->assertSee('Add Item')
        ->assertSee('Cola');
});

it('shows variants after selecting a product via selectProduct', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->assertSet('selectedProductId', $this->product->id)
        ->assertSet('variants', function (array $variants) {
            expect($variants)->toHaveCount(2);

            return true;
        });
});

it('shows no variants warning when product has no variants', function () {
    $productNoVariants = Product::factory()->create(['name' => 'No Variants Product']);

    Livewire::test(CreateSale::class)
        ->call('selectProduct', $productNoVariants->id)
        ->assertSet('variants', [])
        ->assertSee('No variants available for this product.');
});

it('increments quantity', function () {
    Livewire::test(CreateSale::class)
        ->assertSet('itemQuantity', 1)
        ->call('incrementQuantity')
        ->assertSet('itemQuantity', 2)
        ->call('incrementQuantity')
        ->assertSet('itemQuantity', 3);
});

it('decrements quantity but not below 1', function () {
    Livewire::test(CreateSale::class)
        ->assertSet('itemQuantity', 1)
        ->call('decrementQuantity')
        ->assertSet('itemQuantity', 1)
        ->call('incrementQuantity')
        ->assertSet('itemQuantity', 2)
        ->call('decrementQuantity')
        ->assertSet('itemQuantity', 1);
});

it('fails validation when adding to cart without product', function () {
    Livewire::test(CreateSale::class)
        ->call('addToCart')
        ->assertHasErrors(['selectedProductId' => 'required'])
        ->assertHasErrors(['selectedVariantId' => 'required']);
});

it('fails validation when adding to cart without variant', function () {
    Livewire::test(CreateSale::class)
        ->set('selectedProductId', $this->product->id)
        ->call('addToCart')
        ->assertHasErrors(['selectedVariantId' => 'required']);
});

it('adds item to cart', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedVariantId', $this->variantBottle->id)
        ->set('itemQuantity', 3)
        ->call('addToCart')
        ->assertSet('cart', function (array $cart) {
            expect($cart)->toHaveCount(1);
            expect($cart[0]['variant_id'])->toBe($this->variantBottle->id);
            expect($cart[0]['product_id'])->toBe($this->product->id);
            expect($cart[0]['quantity'])->toBe(3);
            expect($cart[0]['unit_price'])->toBe(2.50);
            expect($cart[0]['total_price'])->toBe(7.50);

            return true;
        })
        ->assertSet('selectedProductId', null)
        ->assertSet('selectedVariantId', null)
        ->assertSet('variants', [])
        ->assertSet('itemQuantity', 1);
});

it('merges duplicate variant quantities', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedVariantId', $this->variantBottle->id)
        ->set('itemQuantity', 2)
        ->call('addToCart')
        ->assertSet('cart', fn($cart) => expect($cart)->toHaveCount(1) && true)
        ->call('selectProduct', $this->product->id)
        ->set('selectedVariantId', $this->variantBottle->id)
        ->set('itemQuantity', 3)
        ->call('addToCart')
        ->assertSet('cart', function (array $cart) {
            expect($cart)->toHaveCount(1);
            expect($cart[0]['quantity'])->toBe(5);
            expect($cart[0]['total_price'])->toBe(12.50);

            return true;
        });
});

it('adds different variants as separate cart items', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedVariantId', $this->variantBottle->id)
        ->set('itemQuantity', 2)
        ->call('addToCart')
        ->call('selectProduct', $this->product->id)
        ->set('selectedVariantId', $this->variantPack->id)
        ->set('itemQuantity', 1)
        ->call('addToCart')
        ->assertSet('cart', fn($cart) => expect($cart)->toHaveCount(2) && true);
});

it('increases cart item quantity', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedVariantId', $this->variantBottle->id)
        ->set('itemQuantity', 2)
        ->call('addToCart')
        ->call('increaseCartItemQuantity', 0)
        ->assertSet('cart.0.quantity', 3)
        ->assertSet('cart.0.total_price', 7.50);
});

it('decreases cart item quantity', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedVariantId', $this->variantBottle->id)
        ->set('itemQuantity', 3)
        ->call('addToCart')
        ->call('decreaseCartItemQuantity', 0)
        ->assertSet('cart.0.quantity', 2)
        ->assertSet('cart.0.total_price', 5.00);
});

it('removes cart item when decreasing quantity to zero', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedVariantId', $this->variantBottle->id)
        ->set('itemQuantity', 1)
        ->call('addToCart')
        ->assertSet('cart', fn($cart) => expect($cart)->toHaveCount(1) && true)
        ->call('decreaseCartItemQuantity', 0)
        ->assertSet('cart', []);
});

it('removes cart item', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedVariantId', $this->variantBottle->id)
        ->set('itemQuantity', 2)
        ->call('addToCart')
        ->assertSet('cart', fn($cart) => expect($cart)->toHaveCount(1) && true)
        ->call('removeFromCart', 0)
        ->assertSet('cart', []);
});

it('completes sale and creates records', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedVariantId', $this->variantBottle->id)
        ->set('itemQuantity', 2)
        ->call('addToCart')
        ->set('notes', 'Test sale')
        ->call('completeSale')
        ->assertSet('cart', [])
        ->assertSet('notes', null);

    $this->assertDatabaseHas('sales', [
        'subtotal' => 5.00,
        'total' => 5.00,
        'notes' => 'Test sale',
        'payment_method' => 'cash',
    ]);

    $this->assertDatabaseHas('sale_items', [
        'product_variant_id' => $this->variantBottle->id,
        'quantity' => 2,
        'unit_price' => 2.50,
        'total_price' => 5.00,
    ]);
});

it('decrements stock on sale completion', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedVariantId', $this->variantBottle->id)
        ->set('itemQuantity', 2)
        ->call('addToCart')
        ->call('completeSale');

    $this->assertDatabaseHas('product_variants', [
        'id' => $this->variantBottle->id,
        'stock_quantity' => 98,
    ]);
});

it('shows error when completing sale with empty cart', function () {
    Livewire::test(CreateSale::class)
        ->call('completeSale')
        ->assertSet('cart', [])
        ->assertSee('Add at least one item to the sale.');
});

it('calculates cart total from multiple items', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedVariantId', $this->variantBottle->id)
        ->set('itemQuantity', 2)
        ->call('addToCart')
        ->call('selectProduct', $this->product->id)
        ->set('selectedVariantId', $this->variantPack->id)
        ->set('itemQuantity', 1)
        ->call('addToCart')
        ->assertSet('cart', function (array $cart) {
            expect($cart)->toHaveCount(2);
            $total = array_sum(array_column($cart, 'total_price'));
            expect($total)->toBe(29.00);

            return true;
        })
        ->assertSeeHtml('29.00');
});

it('boot normalizes stale cart items', function () {
    $component = Livewire::test(CreateSale::class);

    $component->instance()->cart = [
        ['variant_id' => $this->variantBottle->id],
    ];

    $component->instance()->boot();

    expect($component->instance()->cart[0]['quantity'])->toBe(0);
    expect($component->instance()->cart[0]['unit_price'])->toBe(0.0);
    expect($component->instance()->cart[0]['total_price'])->toBe(0.0);
    expect($component->instance()->cart[0]['product_name'])->toBe('');
    expect($component->instance()->cart[0]['variant_name'])->toBe('');
    expect($component->instance()->cart[0]['variant_id'])->toBe($this->variantBottle->id);
});

it('renders cart section after adding items', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedVariantId', $this->variantBottle->id)
        ->set('itemQuantity', 3)
        ->call('addToCart')
        ->assertSee('Cola')
        ->assertSeeHtml('Cart (1')
        ->assertSee('Bottles')
        ->assertSeeHtml('Ks 7.50')
        ->assertSee('Complete Sale')
        ->assertSee('Total');
});

it('renders multiple products in the product list', function () {
    Product::factory()->create(['name' => 'Sprite', 'category_id' => Category::factory()]);
    Product::factory()->create(['name' => 'Fanta', 'category_id' => Category::factory()]);

    Livewire::test(CreateSale::class)
        ->assertSee('Cola')
        ->assertSee('Sprite')
        ->assertSee('Fanta');
});
