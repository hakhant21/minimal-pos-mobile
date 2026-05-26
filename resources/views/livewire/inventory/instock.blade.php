<div class="space-y-6">
    <div class="flex items-center gap-3">
        <div
            class="flex h-10 w-10 items-center justify-center rounded-xl bg-linear-to-br from-blue-500 to-blue-600 shadow-lg shadow-blue-500/20">
            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Add Stock</h2>
    </div>

    @if (session('message'))
    <div
        class="flex items-center gap-2 rounded-xl border border-emerald-200/80 bg-linear-to-r from-emerald-50 to-green-50 px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm dark:border-emerald-800/50 dark:from-emerald-950/30 dark:to-green-950/20 dark:text-emerald-300">
        <svg class="h-4 w-4 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ session('message') }}
    </div>
    @endif

    @if ($lowStockProducts->isNotEmpty())
    <div
        class="overflow-hidden rounded-xl border border-red-200/80 bg-linear-to-r from-red-50 to-orange-50 shadow-sm dark:border-red-800/50 dark:from-red-950/30 dark:to-orange-950/20">
        <div class="flex items-center gap-2 border-b border-red-200/50 px-4 py-2.5 dark:border-red-800/30">
            <svg class="h-4 w-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
            </svg>
            <h3 class="text-xs font-semibold uppercase tracking-wider text-red-800 dark:text-red-300">Low Stock Products
            </h3>
        </div>
        <div class="divide-y divide-red-100 dark:divide-red-900/30">
            @foreach ($lowStockProducts as $p)
            <div class="flex items-center justify-between px-4 py-2">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-red-700 dark:text-red-400">{{ $p->name }}</span>
                    <span class="text-xs text-red-500">({{ $p->category->name }})</span>
                </div>
                <span
                    class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-bold text-red-700 dark:bg-red-900/50 dark:text-red-400">{{
                    $p->stock }} left</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <form wire:submit="addStock" class="space-y-5">
        <div
            class="rounded-xl border border-gray-200/80 bg-white p-5 shadow-sm dark:border-gray-700/80 dark:bg-gray-800/80">
            <div class="space-y-4">
                <div class="relative" x-data="{ open: false }" x-on:click.away="open = false">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Product</label>
                    <input type="text" wire:model.live="productSearch" x-on:focus="open = true" x-on:input="open = true"
                        placeholder="Search product..."
                        class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500">
                    <div x-show="open" x-transition
                        class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto dark:bg-gray-800 dark:border-gray-700"
                        style="display: none;">
                        @if($products && count($products) > 0)
                        @foreach($products as $product)
                        <div wire:click="selectProduct({{ $product->id }})" x-on:click="open = false"
                            wire:key="instock-product-{{ $product->id }}"
                            class="px-4 py-2.5 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ $product_id == $product->id ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name
                                    }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Stock: {{ $product->stock
                                    }}</span>
                            </div>
                        </div>
                        @endforeach

                        @if($hasMorePages)
                        <div class="border-t border-gray-100 p-2 dark:border-gray-700">
                            <button wire:click="loadMore" wire:loading.attr="disabled"
                                class="w-full text-center text-xs text-blue-600 hover:text-blue-700 dark:text-blue-400 py-1.5 transition-colors">
                                <span wire:loading.remove wire:target="loadMore">Load more products...</span>
                                <span wire:loading wire:target="loadMore">Loading...</span>
                            </button>
                        </div>
                        @endif
                        @else
                        <div class="px-4 py-2.5 text-center text-sm text-gray-500 dark:text-gray-400">
                            No products found
                        </div>
                        @endif
                    </div>
                    @error('product_id') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                </div>

                @if ($product)
                <div>
                    <label for="unit_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Unit
                        Type</label>
                    <select wire:model.live="unit_id" id="unit_id"
                        class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm transition focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @foreach($product->units as $unit)
                        <option value="{{ $unit->id }}">
                            {{ $unit->name }} - ${{ number_format($unit->price, 2) }}
                            @if($unit->quantity > 1)
                            ({{ $unit->quantity }} units per pack)
                            @endif
                        </option>
                        @endforeach
                    </select>
                    @error('unit_id') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                </div>

                <div
                    class="rounded-xl border border-gray-200 bg-gray-50/80 p-4 dark:border-gray-700 dark:bg-gray-700/50">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Current Stock</span>
                        <span
                            class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-sm font-bold {{ $product->stock < 10 ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' }}">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            {{ $product->stock }}
                        </span>
                    </div>

                    @php
                    $selectedUnit = $unit_id && $product ? $product->units->find($unit_id) : null;
                    @endphp

                    @if($selectedUnit)
                    <div
                        class="mt-3 flex items-center justify-between border-t border-gray-200 pt-3 dark:border-gray-600">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Selected Unit Price</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                            ${{ number_format($selectedUnit->price, 2) }}
                            @if($selectedUnit->quantity > 1)
                            <span class="text-xs font-normal text-gray-500">(per {{ $selectedUnit->quantity }}
                                units)</span>
                            @endif
                        </span>
                    </div>
                    @endif

                    <div class="mt-3 border-t border-gray-200 pt-3 dark:border-gray-600">
                        <p class="mb-2 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Available Units</p>
                        <div class="space-y-1.5">
                            @foreach ($product->units as $unit)
                            <div
                                class="flex items-center justify-between rounded-lg bg-white px-3 py-2 text-sm dark:bg-gray-800 {{ $unit_id == $unit->id ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                <span class="text-gray-700 dark:text-gray-300">{{ $unit->name }}</span>
                                <div class="text-right">
                                    <span class="font-semibold text-gray-900 dark:text-white">${{
                                        number_format($unit->price, 2) }}</span>
                                    @if($unit->quantity > 1)
                                    <span class="ml-1 text-xs text-gray-500">({{ $unit->quantity }} pcs)</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity to
                        Add</label>
                    <input wire:model.live="quantity" type="number" min="1" id="quantity"
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-blue-500"
                        placeholder="e.g. 50">
                    @error('quantity') <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p> @enderror
                </div>

                @if($unit_id && $product && $quantity)
                @php
                $selectedUnit = $product->units->find($unit_id);
                @endphp
                @if($selectedUnit)
                <div class="rounded-xl bg-blue-50 p-3 dark:bg-blue-950/20">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Total Value:</span>
                        <span class="font-bold text-gray-900 dark:text-white">
                            ${{ number_format($selectedUnit->price * $quantity, 2) }}
                        </span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                        <span>{{ $quantity }} × ${{ number_format($selectedUnit->price, 2) }}</span>
                        <span>per {{ $selectedUnit->name }}</span>
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>

        <button type="submit"
            class="flex w-full items-center justify-center gap-2 rounded-xl bg-linear-to-r from-blue-600 to-blue-700 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition-all hover:from-blue-700 hover:to-blue-800 hover:shadow-xl hover:shadow-blue-600/25 active:scale-[0.98]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Stock
        </button>
    </form>

    <x-load-more :hasMorePages="$hasMorePages" target="loadMore" buttonText="Load More Products"
        loadingText="Loading products..." color="blue" />
</div>
