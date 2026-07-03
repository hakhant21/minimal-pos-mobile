<?php

use App\Livewire\Sales\CreateSale;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;

uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    $category = Category::factory()->create(['name' => 'Beverages']);
    $this->product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Cola',
        'stock' => 100,
    ]);
    $this->unitBottle = Unit::factory()->create([
        'product_id' => $this->product->id,
        'name' => 'Bottle',
        'quantity' => 1,
        'price' => 2.50,
    ]);
    $this->unitCase = Unit::factory()->create([
        'product_id' => $this->product->id,
        'name' => 'Case (12)',
        'quantity' => 12,
        'price' => 24.00,
    ]);
});

it('renders the component with products and header', function () {
    Livewire::test(CreateSale::class)
        ->assertSee('New Sale')
        ->assertSee('Add Item')
        ->assertSee('Cola')
        ->assertSee('Add to Sale');
});

it('shows units after selecting a product via selectProduct', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->assertSet('selectedProductId', $this->product->id)
        ->assertSet('units', function (array $units) {
            expect($units)->toHaveCount(2);

            return true;
        })
        ->assertSee('Bottle')
        ->assertSee('Case (12)');
});

it('shows units after selecting a product via updatedSelectedProductId', function () {
    Livewire::test(CreateSale::class)
        ->set('selectedProductId', $this->product->id)
        ->assertSet('units', function (array $units) {
            expect($units)->toHaveCount(2);

            return true;
        })
        ->assertSee('Bottle')
        ->assertSee('Case (12)');
});

it('shows no units warning when product has no units', function () {
    $productNoUnits = Product::factory()->create(['name' => 'No Units Product']);

    Livewire::test(CreateSale::class)
        ->call('selectProduct', $productNoUnits->id)
        ->assertSet('units', [])
        ->assertSee('No units available for this product.');
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
        ->assertSet('itemQuantity', 1) // should stay at 1
        ->call('incrementQuantity')
        ->assertSet('itemQuantity', 2)
        ->call('decrementQuantity')
        ->assertSet('itemQuantity', 1);
});

it('fails validation when adding to cart without product', function () {
    Livewire::test(CreateSale::class)
        ->call('addToCart')
        ->assertHasErrors(['selectedProductId' => 'required'])
        ->assertHasErrors(['selectedUnitId' => 'required']);
});

it('fails validation when adding to cart without unit', function () {
    Livewire::test(CreateSale::class)
        ->set('selectedProductId', $this->product->id)
        ->call('addToCart')
        ->assertHasErrors(['selectedUnitId' => 'required']);
});

it('adds item to cart', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedUnitId', $this->unitBottle->id)
        ->set('itemQuantity', 3)
        ->call('addToCart')
        ->assertSet('cart', function (array $cart) {
            expect($cart)->toHaveCount(1);
            expect($cart[0]['product_id'])->toBe($this->product->id);
            expect($cart[0]['unit_id'])->toBe($this->unitBottle->id);
            expect($cart[0]['quantity'])->toBe(3);
            expect($cart[0]['unit_price'])->toBe(2.50);
            expect($cart[0]['subtotal'])->toBe(7.50);

            return true;
        })
        ->assertSet('selectedProductId', null)
        ->assertSet('selectedUnitId', null)
        ->assertSet('units', [])
        ->assertSet('itemQuantity', 1);
});

it('adds duplicate item merges quantities', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedUnitId', $this->unitBottle->id)
        ->set('itemQuantity', 2)
        ->call('addToCart')
        ->assertSet('cart', function (array $cart) {
            expect($cart)->toHaveCount(1);

            return true;
        })
        ->call('selectProduct', $this->product->id)
        ->set('selectedUnitId', $this->unitBottle->id)
        ->set('itemQuantity', 3)
        ->call('addToCart')
        ->assertSet('cart', function (array $cart) {
            expect($cart)->toHaveCount(1);
            expect($cart[0]['quantity'])->toBe(5);
            expect($cart[0]['subtotal'])->toBe(12.50);

            return true;
        });
});

it('adds different units as separate cart items', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedUnitId', $this->unitBottle->id)
        ->set('itemQuantity', 2)
        ->call('addToCart')
        ->call('selectProduct', $this->product->id)
        ->set('selectedUnitId', $this->unitCase->id)
        ->set('itemQuantity', 1)
        ->call('addToCart')
        ->assertSet('cart', function (array $cart) {
            expect($cart)->toHaveCount(2);

            return true;
        });
});

it('increases cart item quantity', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedUnitId', $this->unitBottle->id)
        ->set('itemQuantity', 2)
        ->call('addToCart')
        ->call('increaseCartItemQuantity', 0)
        ->assertSet('cart.0.quantity', 3)
        ->assertSet('cart.0.subtotal', 7.50);
});

it('decreases cart item quantity', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedUnitId', $this->unitBottle->id)
        ->set('itemQuantity', 3)
        ->call('addToCart')
        ->call('decreaseCartItemQuantity', 0)
        ->assertSet('cart.0.quantity', 2)
        ->assertSet('cart.0.subtotal', 5.00);
});

it('removes cart item when decreasing quantity to zero', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedUnitId', $this->unitBottle->id)
        ->set('itemQuantity', 1)
        ->call('addToCart')
        ->assertSet('cart', function (array $cart) {
            expect($cart)->toHaveCount(1);

            return true;
        })
        ->call('decreaseCartItemQuantity', 0)
        ->assertSet('cart', []);
});

it('removes cart item', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedUnitId', $this->unitBottle->id)
        ->set('itemQuantity', 2)
        ->call('addToCart')
        ->assertSet('cart', function (array $cart) {
            expect($cart)->toHaveCount(1);

            return true;
        })
        ->call('removeFromCart', 0)
        ->assertSet('cart', []);
});

it('shows error when unit does not belong to selected product', function () {
    $otherProduct = Product::factory()->create(['name' => 'Other']);
    $otherUnit = Unit::factory()->create([
        'product_id' => $otherProduct->id,
        'name' => 'Other Unit',
        'price' => 5.00,
    ]);

    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedUnitId', $otherUnit->id)
        ->set('itemQuantity', 1)
        ->call('addToCart')
        ->assertSee('Selected unit not found for this product.');
});

it('completes sale and creates records', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedUnitId', $this->unitBottle->id)
        ->set('itemQuantity', 2)
        ->call('addToCart')
        ->set('notes', 'Test sale')
        ->call('completeSale')
        ->assertSet('cart', [])
        ->assertSet('notes', null);

    $this->assertDatabaseHas('sales', [
        'total_amount' => 5.00,
        'notes' => 'Test sale',
    ]);

    $this->assertDatabaseHas('sale_items', [
        'product_id' => $this->product->id,
        'unit_id' => $this->unitBottle->id,
        'quantity' => 2,
        'unit_price' => 2.50,
        'subtotal' => 5.00,
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
        ->set('selectedUnitId', $this->unitBottle->id)
        ->set('itemQuantity', 2)
        ->call('addToCart')
        ->call('selectProduct', $this->product->id)
        ->set('selectedUnitId', $this->unitCase->id)
        ->set('itemQuantity', 1)
        ->call('addToCart')
        ->assertSet('cart', function (array $cart) {
            expect($cart)->toHaveCount(2);
            $total = array_sum(array_column($cart, 'subtotal'));
            expect($total)->toBe(29.00); // 5.00 + 24.00

            return true;
        })
        ->assertSeeHtml('29.00');
});

it('boot normalizes stale cart items', function () {
    $component = Livewire::test(CreateSale::class);

    // Inject corrupted cart item directly on the underlying component
    $component->instance()->cart = [
        ['product_id' => $this->product->id],
    ];

    // Manually trigger boot to normalize
    $component->instance()->boot();

    expect($component->instance()->cart[0]['quantity'])->toBe(0);
    expect($component->instance()->cart[0]['unit_price'])->toBe(0.0);
    expect($component->instance()->cart[0]['subtotal'])->toBe(0.0);
    expect($component->instance()->cart[0]['product_name'])->toBe('');
    expect($component->instance()->cart[0]['unit_name'])->toBe('');
    expect($component->instance()->cart[0]['product_id'])->toBe($this->product->id);
    expect($component->instance()->cart[0]['unit_id'])->toBe(0);
});

it('renders cart section after adding items', function () {
    Livewire::test(CreateSale::class)
        ->call('selectProduct', $this->product->id)
        ->set('selectedUnitId', $this->unitBottle->id)
        ->set('itemQuantity', 3)
        ->call('addToCart')
        ->assertSee('Cola')
        ->assertSeeHtml('Cart (1')
        ->assertSee('Bottle')
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
