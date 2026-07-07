<?php

use App\Livewire\Inventory\Instock;
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

    $this->unit = Unit::factory()->piece()->create();

    $this->product = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Cola',
    ]);

    $this->variant = ProductVariant::factory()->create([
        'product_id' => $this->product->id,
        'unit_id' => $this->unit->id,
        'cost_price' => 1.50,
        'selling_price' => 2.50,
        'stock_quantity' => 100,
        'min_stock_level' => 10,
    ]);

    $this->otherProduct = Product::factory()->create([
        'category_id' => $category->id,
        'name' => 'Sprite',
    ]);
});

it('renders the component with product list and header', function () {
    Livewire::test(Instock::class)
        ->assertSee('Add Stock')
        ->assertSee('Cola')
        ->assertSee('Sprite');
});

it('filters products by search', function () {
    Livewire::test(Instock::class)
        ->set('productSearch', 'Cola')
        ->assertSee('Cola')
        ->assertDontSee('Sprite');
});

it('shows no products found when search has no matches', function () {
    Livewire::test(Instock::class)
        ->set('productSearch', 'NonExistent')
        ->assertSee('No products found');
});

it('selects a product and auto-selects the first variant', function () {
    Livewire::test(Instock::class)
        ->call('selectProduct', $this->product->id)
        ->assertSet('product_id', $this->product->id)
        ->assertSet('variant_id', $this->variant->id)
        ->assertSet('purchaseCost', 1.50)
        ->assertSet('productSearch', 'Cola')
        ->assertSee('Selected Variant Stock');
});

it('shows variant details after selecting a product', function () {
    Livewire::test(Instock::class)
        ->call('selectProduct', $this->product->id)
        ->assertSee('100')
        ->assertSee('Selling Price')
        ->assertSee('Current Cost');
});

it('updates purchase cost when variant selection changes', function () {
    $variant2 = ProductVariant::factory()->create([
        'product_id' => $this->product->id,
        'unit_id' => $this->unit->id,
        'cost_price' => 2.00,
        'selling_price' => 3.50,
        'stock_quantity' => 50,
    ]);

    Livewire::test(Instock::class)
        ->call('selectProduct', $this->product->id)
        ->assertSet('purchaseCost', 1.50)
        ->set('variant_id', $variant2->id)
        ->assertSet('purchaseCost', 2.00);
});

it('fails validation when submitting without a product', function () {
    Livewire::test(Instock::class)
        ->call('addStock')
        ->assertHasErrors(['product_id' => 'required'])
        ->assertHasErrors(['variant_id' => 'required'])
        ->assertHasErrors(['quantity' => 'required']);
});

it('fails validation when quantity is zero or negative', function () {
    Livewire::test(Instock::class)
        ->call('selectProduct', $this->product->id)
        ->set('quantity', 0)
        ->call('addStock')
        ->assertHasErrors(['quantity' => 'min']);
});

it('adds stock and increments the stock quantity', function () {
    Livewire::test(Instock::class)
        ->call('selectProduct', $this->product->id)
        ->set('quantity', 10)
        ->call('addStock')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('product_variants', [
        'id' => $this->variant->id,
        'stock_quantity' => 110,
    ]);
});

it('updates weighted average cost price when purchase cost differs', function () {
    Livewire::test(Instock::class)
        ->call('selectProduct', $this->product->id)
        ->set('quantity', 10)
        ->set('purchaseCost', 2.00)
        ->call('addStock')
        ->assertHasNoErrors();

    // (100 * 1.50 + 10 * 2.00) / (100 + 10) = (150 + 20) / 110 = 170 / 110 = 1.5454...
    $this->assertDatabaseHas('product_variants', [
        'id' => $this->variant->id,
        'cost_price' => 1.55,
        'stock_quantity' => 110,
    ]);
});

it('leaves cost price unchanged when purchase cost is not set', function () {
    Livewire::test(Instock::class)
        ->call('selectProduct', $this->product->id)
        ->set('quantity', 10)
        ->set('purchaseCost', 0)
        ->call('addStock')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('product_variants', [
        'id' => $this->variant->id,
        'cost_price' => 1.50,
        'stock_quantity' => 110,
    ]);
});

it('resets the form after adding stock', function () {
    Livewire::test(Instock::class)
        ->call('selectProduct', $this->product->id)
        ->set('quantity', 5)
        ->call('addStock')
        ->assertSet('product_id', null)
        ->assertSet('variant_id', null)
        ->assertSet('quantity', null)
        ->assertSet('product', null)
        ->assertSet('productSearch', '')
        ->assertSet('purchaseCost', null)
        ->assertHasNoErrors();
});

it('shows success message after adding stock', function () {
    Livewire::test(Instock::class)
        ->call('selectProduct', $this->product->id)
        ->set('quantity', 5)
        ->call('addStock')
        ->assertSee('Added 5 item(s) to stock successfully.');
});

it('shows low stock variants', function () {
    ProductVariant::factory()->lowStock()->create([
        'product_id' => $this->product->id,
        'unit_id' => $this->unit->id,
    ]);

    Livewire::test(Instock::class)
        ->assertSee('Low Stock')
        ->assertSee('Cola');
});

it('loads more products on demand', function () {
    Product::factory()->count(15)->create([
        'category_id' => Category::factory(),
    ]);

    $component = Livewire::test(Instock::class);

    expect($component->instance()->perPage)->toBe(10);

    $component->call('loadMore');

    expect($component->instance()->perPage)->toBe(20);
});

it('adds stock with non-default variant correctly', function () {
    $secondUnit = Unit::factory()->pack()->create();
    $variant2 = ProductVariant::factory()->create([
        'product_id' => $this->product->id,
        'unit_id' => $secondUnit->id,
        'cost_price' => 10.00,
        'selling_price' => 15.00,
        'stock_quantity' => 20,
    ]);

    Livewire::test(Instock::class)
        ->call('selectProduct', $this->product->id)
        ->set('variant_id', $variant2->id)
        ->assertSet('purchaseCost', 10.00)
        ->set('quantity', 5)
        ->call('addStock')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('product_variants', [
        'id' => $variant2->id,
        'stock_quantity' => 25,
        'cost_price' => 10.00,
    ]);
});
