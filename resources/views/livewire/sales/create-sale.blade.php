<div class="space-y-6">
    <x-section-header title="{{ __('New Sale') }}" color="emerald"
        icon="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />

    @if (session('message'))
        <x-alert type="success" :message="session('message')" />
    @endif

    @if (session('error'))
        <x-alert type="error" :message="session('error')" />
    @endif

    <x-card>
        <x-slot:header>
            <x-section-header title="{{ __('Add Item') }}" color="emerald" size="sm"
                icon="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </x-slot:header>

        <div class="space-y-4">
            <div class="relative" wire:click.away="$set('showProductDropdown', false)" wire:key="search-wrapper">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Product') }}</label>
                <input type="text" wire:model.live.debounce.200ms="productSearch" wire:focus="$set('showProductDropdown', true)"
                    placeholder="{{ __('Search product...') }}"
                    class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500">

                <div wire:key="product-dropdown"
                    class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto dark:bg-gray-800 dark:border-gray-700 {{ $showProductDropdown ? '' : 'hidden' }}">
                    @if($products && count($products) > 0)
                    @foreach($products as $product)
                    <div wire:click="selectProduct({{ $product->id }})"
                        wire:key="product-{{ $product->id }}"
                        class="px-4 py-2.5 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ $selectedProductId == $product->id ? 'bg-emerald-50 dark:bg-emerald-900/20' : '' }}">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{
                                $product->variants->sum('stock_quantity') }} {{ __('in stock') }}</span>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="px-4 py-2.5 text-center text-sm text-gray-500 dark:text-gray-400">
                        {{ __('No products found') }}
                    </div>
                    @endif
                </div>
            </div>

            @error('selectedProductId')
            <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
            @enderror

            @if(count($variants) > 0)
            <div>
                <label for="variant" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Variant') }}</label>
                <select wire:model.live="selectedVariantId" id="variant"
                    class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-xs shadow-sm transition focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-emerald-500">
                    <option value="">{{ __('Select variant') }}</option>
                    @foreach ($variants as $v)
                    <option value="{{ $v['id'] }}">
                        {{ $v['unit_name'] }}@if($v['units_per_package']) ({{ $v['units_per_package'] }})@endif
                        — Ks {{ number_format($v['package_price'], 2) }}
                        @if($v['has_package']) | {{ __('Per Unit Price') }}: Ks {{ number_format($v['per_unit_price'], 2) }}
                        @endif
                        @if($v['stock'] <= 0) ({{ __('Out of stock') }}) @endif </option>
                    @endforeach
                </select>
                @error('selectedVariantId')
                <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if($selectedVariantId && $this->selectedVariant && $this->selectedVariant['has_package'])
            <x-input-label label="{{ __('Sell as') }}">
                <x-toggle-group
                    :options="[
                        'single' => ['label' => __('Single Unit'), 'subtitle' => 'Ks ' . number_format($this->selectedVariant['per_unit_price'], 2) . '/' . $this->selectedVariant['unit_name']],
                        'package' => ['label' => __('Package'), 'subtitle' => 'Ks ' . number_format($this->selectedVariant['package_price'], 2)],
                    ]"
                    model="sellType"
                    :selected="$sellType"
                    color="emerald"
                    :columns="2" />
            </x-input-label>
            @endif

            @elseif($selectedProductId)
            <div class="rounded-lg bg-yellow-50 p-3 text-sm text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>{{ __('No variants available for this product.') }}</span>
                </div>
            </div>
            @endif

            <x-input-label label="{{ __('Quantity') }}" name="itemQuantity">
                <x-quantity wireDecrement="decrementQuantity" wireIncrement="incrementQuantity" model="itemQuantity" />
            </x-input-label>

            <x-btn-primary wire="addToCart" color="emerald">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('Add to Sale') }}
            </x-btn-primary>
        </div>
    </x-card>

    @if (!empty($cart) && count($cart) > 0)
    <x-card>
        <x-slot:header>
            <div class="flex items-center gap-2">
                <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                    {{ __('Cart') }} ({{ count($cart) }} {{ __('items') }})
                </span>
            </div>
        </x-slot:header>

        <div class="divide-y divide-gray-100 dark:divide-gray-800 -mx-5 -mb-5">
            @foreach ($cart as $index => $item)
            <div class="px-5 py-4 transition hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                <div class="flex items-start gap-4">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-linear-to-br from-emerald-500 to-emerald-600 text-base font-bold text-white shadow-sm">
                        {{ $item['quantity'] }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item['product_name'] }}</p>
                            <x-badge :color="$item['sell_type'] === 'single' ? 'blue' : 'emerald'">
                                {{ $item['sell_type'] === 'single' ? __('Single Unit') : __('Package') }}
                            </x-badge>
                        </div>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                            {{ $item['variant_name'] }} @ Ks {{ number_format($item['unit_price'], 2) }}
                        </p>
                    </div>

                    <div class="text-right shrink-0">
                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                            Ks {{ number_format($item['total_price'], 2) }}
                        </p>
                        <p class="mt-0.5 text-[11px] text-gray-400 dark:text-gray-500">
                            Ks {{ number_format($item['unit_price'], 2) }}/{{ $item['unit_name'] }}
                        </p>
                    </div>
                </div>

                <div class="mt-3 flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-700/50">
                    <div class="flex items-center gap-3">
                        <x-quantity
                            wireDecrement="decreaseCartItemQuantity({{ $index }})"
                            wireIncrement="increaseCartItemQuantity({{ $index }})"
                            :quantity="$item['quantity']"
                            size="sm"
                            showLabel />
                    </div>

                    <x-icon-btn wire="removeFromCart({{ $index }})" color="red" size="sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </x-icon-btn>
                </div>
            </div>
            @endforeach

            <div class="flex items-center justify-between border-t border-gray-100 bg-gray-50/80 px-5 py-3 dark:border-gray-700/50 dark:bg-gray-800/50">
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Total') }}</span>
                <span class="text-lg font-bold text-gray-900 dark:text-white">
                    Ks {{ number_format($cartTotal, 2) }}
                </span>
            </div>
        </div>
    </x-card>

    <x-card padding="p-5">
        <x-input-label label="{{ __('Customer') }}" optional="true" name="customerName">
            <input wire:model="customerName" id="customerName" type="text"
                class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-blue-500"
                placeholder="{{ __('Enter customer name') }}">
        </x-input-label>
    </x-card>

    <x-card padding="p-5">
        <x-input-label label="{{ __('Payment Method') }}">
            <x-toggle-group
                :options="[
                    'cash' => ['label' => __('Cash'), 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                    'kbzpay' => ['label' => __('KBZPay'), 'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
                ]"
                model="paymentMethod"
                :selected="$paymentMethod"
                color="emerald"
                :columns="2" />
        </x-input-label>
    </x-card>

    <x-btn-primary wire="completeSale" color="blue" size="lg">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ __('Complete Sale') }} — Ks {{ number_format($cartTotal, 2) }}
    </x-btn-primary>
    @endif
</div>
