<div class="space-y-6">
    <div class="flex items-center gap-3">
        <div
            class="flex h-10 w-10 items-center justify-center rounded-xl bg-linear-to-br from-emerald-500 to-emerald-600 shadow-lg shadow-emerald-500/20">
            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('New Sale') }}</h2>
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

    @if (session('error'))
    <div
        class="flex items-center gap-2 rounded-xl border border-red-200/80 bg-linear-to-r from-red-50 to-orange-50 px-4 py-3 text-sm font-medium text-red-800 shadow-sm dark:border-red-800/50 dark:from-red-950/30 dark:to-orange-950/20 dark:text-red-300">
        <svg class="h-4 w-4 shrink-0 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ session('error') }}
    </div>
    @endif

    <div
        class="rounded-xl border border-gray-200/80 bg-white p-5 shadow-sm dark:border-gray-700/80 dark:bg-gray-800/80">
        <div class="mb-4 flex items-center gap-2">
            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/30">
                <svg class="h-3.5 w-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Add Item') }}</h3>
        </div>

        <div class="space-y-4">
            <div class="relative" x-data="{ open: false }" x-on:click.away="open = false">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Product')
                    }}</label>
                <input type="text" wire:model.live="productSearch" x-on:focus="open = true" x-on:input="open = true"
                    placeholder="{{ __('Search product...') }}"
                    class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500">

                <div x-show="open" x-transition
                    class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto dark:bg-gray-800 dark:border-gray-700"
                    style="display: none;">
                    @if($products && count($products) > 0)
                    @foreach($products as $product)
                    <div wire:click="selectProduct({{ $product->id }})" x-on:click="open = false"
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
                <label for="variant" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Variant')
                    }}</label>
                <select wire:model.live="selectedVariantId" id="variant"
                    class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm transition focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-emerald-500">
                    <option value="">{{ __('Select variant') }}</option>
                    @foreach ($variants as $v)
                    <option value="{{ $v['id'] }}">
                        {{ $v['unit_name'] }} @if($v['units_per_package'])({{ $v['units_per_package'] }})@endif — Ks {{
                        number_format($v['price'], 2) }}
                        @if($v['stock'] <= 0) ({{ __('Out of stock') }}) @endif </option>
                            @endforeach
                </select>
                @error('selectedVariantId')
                <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>
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

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Quantity')
                    }}</label>
                <div class="flex items-center gap-2">
                    <button type="button" wire:click="decrementQuantity" wire:loading.attr="disabled"
                        class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 transition hover:bg-gray-50 hover:border-emerald-400 disabled:opacity-50 disabled:cursor-not-allowed dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </button>

                    <input wire:model="itemQuantity" type="number" min="1" id="qty"
                        class="block w-24 text-center rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-emerald-500"
                        readonly>

                    <button type="button" wire:click="incrementQuantity" wire:loading.attr="disabled"
                        class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 transition hover:bg-gray-50 hover:border-emerald-400 disabled:opacity-50 disabled:cursor-not-allowed dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                </div>
                @error('itemQuantity')
                <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="button" wire:click="addToCart"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-linear-to-r from-emerald-500 to-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition-all hover:from-emerald-600 hover:to-emerald-700 hover:shadow-xl hover:shadow-emerald-500/25 active:scale-[0.98]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('Add to Sale') }}
            </button>
        </div>
    </div>

    @if (!empty($cart) && count($cart) > 0)
    <div
        class="overflow-hidden rounded-xl border border-gray-200/80 bg-white shadow-sm dark:border-gray-700/80 dark:bg-gray-800/80">
        <div
            class="flex items-center justify-between border-b border-gray-100 bg-gray-50/80 px-4 py-3 dark:border-gray-700/50 dark:bg-gray-800/50">
            <div class="flex items-center gap-2">
                <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Cart') }} ({{ count($cart)
                    }}
                    {{ __('items') }})</span>
            </div>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach ($cart as $index => $item)
            <div class="flex items-center gap-3 px-4 py-3 transition hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                <div
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-sm font-bold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                    {{ $item['quantity'] }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $item['product_name'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item['variant_name'] }} @ Ks {{
                        number_format($item['unit_price'], 2) }}</p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" wire:click="decreaseCartItemQuantity({{ $index }})"
                        class="flex h-7 w-7 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-600 transition hover:bg-gray-50 hover:border-emerald-400 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </button>

                    <span class="text-sm font-semibold text-gray-900 dark:text-white min-w-10 text-center">
                        {{ $item['quantity'] }}
                    </span>

                    <button type="button" wire:click="increaseCartItemQuantity({{ $index }})"
                        class="flex h-7 w-7 items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-600 transition hover:bg-gray-50 hover:border-emerald-400 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold text-gray-900 dark:text-white min-w-20 text-right">
                        Ks {{ number_format($item['total_price'], 2) }}
                    </span>
                    <button type="button" wire:click="removeFromCart({{ $index }})"
                        class="flex h-7 w-7 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50 hover:text-red-700 dark:hover:bg-red-900/30 dark:hover:text-red-400">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div
            class="flex items-center justify-between border-t border-gray-100 bg-gray-50/80 px-4 py-3 dark:border-gray-700/50 dark:bg-gray-800/50">
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Total') }}</span>
            <span class="text-lg font-bold text-gray-900 dark:text-white">
                Ks {{ number_format($cartTotal, 2) }}
            </span>
        </div>
    </div>

    <div
        class="rounded-xl border border-gray-200/80 bg-white p-5 shadow-sm dark:border-gray-700/80 dark:bg-gray-800/80">
        <label for="customerName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Customer
            name') }} <span class="text-xs font-normal text-gray-500">{{ __('(optional)') }}</span></label>
        <input wire:model="customerName" id="customerName" type="text"
            class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-blue-500"
            placeholder="{{ __('Enter customer name') }}">
        @error('customerName')
        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div
        class="rounded-xl border border-gray-200/80 bg-white p-5 shadow-sm dark:border-gray-700/80 dark:bg-gray-800/80">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('Payment Method')
            }}</label>
        <div class="grid grid-cols-2 gap-3">
            <label wire:click="$set('paymentMethod', 'cash')"
                class="flex items-center justify-center gap-2 rounded-xl border-2 p-3 cursor-pointer transition-all {{ $paymentMethod === 'cash' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 dark:border-emerald-600' : 'border-gray-200 hover:border-gray-300 dark:border-gray-700 dark:hover:border-gray-600' }}">
                <svg class="h-5 w-5 {{ $paymentMethod === 'cash' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span
                    class="text-sm font-medium {{ $paymentMethod === 'cash' ? 'text-emerald-800 dark:text-emerald-300' : 'text-gray-600 dark:text-gray-400' }}">{{
                    __('Cash') }}</span>
            </label>
            <label wire:click="$set('paymentMethod', 'kbzpay')"
                class="flex items-center justify-center gap-2 rounded-xl border-2 p-3 cursor-pointer transition-all {{ $paymentMethod === 'kbzpay' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 dark:border-emerald-600' : 'border-gray-200 hover:border-gray-300 dark:border-gray-700 dark:hover:border-gray-600' }}">
                <svg class="h-5 w-5 {{ $paymentMethod === 'kbzpay' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span
                    class="text-sm font-medium {{ $paymentMethod === 'kbzpay' ? 'text-emerald-800 dark:text-emerald-300' : 'text-gray-600 dark:text-gray-400' }}">{{
                    __('KBZPay') }}</span>
            </label>
        </div>
    </div>

    <button type="button" wire:click="completeSale"
        class="flex w-full items-center justify-center gap-2 rounded-xl bg-linear-to-r from-blue-600 to-blue-700 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition-all hover:from-blue-700 hover:to-blue-800 hover:shadow-xl hover:shadow-blue-600/25 active:scale-[0.98]">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ __('Complete Sale') }} — Ks {{ number_format($cartTotal, 2) }}
    </button>
    @endif
</div>
