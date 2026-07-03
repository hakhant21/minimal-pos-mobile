<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-linear-to-br from-emerald-500 to-emerald-600 shadow-lg shadow-emerald-500/20">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Products') }}</h2>
        </div>
        <button wire:click="create"
            class="inline-flex items-center gap-1.5 rounded-xl bg-linear-to-r from-emerald-600 to-emerald-700 px-3.5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-600/20 transition-all hover:from-emerald-700 hover:to-emerald-800 hover:shadow-xl hover:shadow-emerald-600/25 active:scale-[0.98]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('New Product') }}
        </button>
    </div>

    {{-- Search Bar --}}
    <div class="relative">
        <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400 dark:text-gray-500" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('Search products...') }}"
            class="w-full rounded-xl border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm shadow-sm transition focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-emerald-500">
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
        class="flex items-center gap-2 rounded-xl border border-red-200/80 bg-linear-to-r from-red-50 to-rose-50 px-4 py-3 text-sm font-medium text-red-800 shadow-sm dark:border-red-800/50 dark:from-red-950/30 dark:to-rose-950/20 dark:text-red-300">
        <svg class="h-4 w-4 shrink-0 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ session('error') }}
    </div>
    @endif

    @php
        $isUnitMode = !is_null($createdProductId);
    @endphp

    @if ($showForm)
    <div
        class="rounded-xl border border-gray-200/80 bg-white p-5 shadow-sm dark:border-gray-700/80 dark:bg-gray-800/80">
        <form class="space-y-4">
            @if ($isUnitMode)
                {{-- Unit form after product creation --}}
                <div class="rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-300">
                    <strong>{{ $name }}</strong> {{ __('created. Add units below.') }}
                </div>

                <div class="border-t border-gray-200 pt-4 dark:border-gray-700">
                    <label class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Unit <span class="text-red-500">*</span>
                    </label>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Unit Name') }}</label>
                            <input wire:model="unitName" type="text"
                                class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                placeholder="{{ __('e.g., Bottle, Case, Can') }}">
                            @error('unitName')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Quantity') }}</label>
                            <input wire:model="unitQuantity" type="number" min="1"
                                class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                placeholder="{{ __('Qty') }}">
                            @error('unitQuantity')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Price') }}</label>
                            <input wire:model="unitPrice" type="number" step="0.01" min="0"
                                class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                placeholder="{{ __('Price') }}">
                            @error('unitPrice')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                @if (count($createdProductUnits ?? []))
                <div class="border-t border-gray-200 pt-4 dark:border-gray-700">
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Added Units') }} ({{ count($createdProductUnits ?? []) }})
                    </label>
                    <div class="space-y-1.5">
                        @foreach ($createdProductUnits as $unit)
                         <div class="flex items-center gap-3 rounded-lg bg-gray-50 px-3 py-2 text-sm dark:bg-gray-700/50">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $unit['name'] }}</span>
                            <span class="text-gray-500 dark:text-gray-400">{{ __('Qty') }}: {{ $unit['quantity'] }}</span>
                            <span class="font-mono text-emerald-600 dark:text-emerald-400">Ks {{ number_format($unit['price'], 2) }}</span>
                            @if (!empty($unit['cost_price']))
                            <span class="font-mono text-gray-400">{{ __('Cost') }}: Ks {{ number_format($unit['cost_price'], 2) }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="flex gap-3 pt-1">
                    <button type="button" wire:click="saveUnit"
                        class="flex-1 rounded-xl bg-linear-to-r from-emerald-600 to-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-600/20 transition-all hover:from-emerald-700 hover:to-emerald-800 active:scale-[0.98]">
                        {{ __('Add Unit') }}
                    </button>
                    <button type="button" wire:click="finish"
                        class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        {{ __('Done') }}
                    </button>
                </div>
            @else
                {{-- Product fields (create & edit) --}}
                <div>
                    <label for="category_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Category') }}</label>
                    <select wire:model="category_id" id="category_id"
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm transition focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-emerald-500">
                        <option value="">{{ __('Select category') }}</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">                        {{ __('Product Name') }}</label>
                    <input wire:model="name" type="text" id="name"
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-emerald-500"
                        placeholder="{{ __('Product name') }}">
                    @error('name')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                    <textarea wire:model="description" id="description" rows="2"
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-emerald-500"
                        placeholder="{{ __('Optional description') }}"></textarea>
                    @error('description')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Initial Stock') }}</label>
                    <input wire:model="stock" type="number" min="0" id="stock"
                        class="mt-1.5 block w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-emerald-500"
                        placeholder="0">
                    @error('stock')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if ($editingProductId)
                {{-- Unit fields (edit mode) --}}
                <div class="border-t border-gray-200 pt-4 dark:border-gray-700">
                    <label class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Unit <span class="text-red-500">*</span>
                    </label>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Unit Name') }}</label>
                            <input wire:model="unitName" type="text"
                                class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                placeholder="{{ __('e.g., Bottle, Case, Can') }}">
                            @error('unitName')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Quantity') }}</label>
                            <input wire:model="unitQuantity" type="number" min="1"
                                class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                placeholder="{{ __('Qty') }}">
                            @error('unitQuantity')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Sell Price') }}</label>
                            <input wire:model="unitPrice" type="number" step="0.01" min="0"
                                class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                placeholder="{{ __('Price') }}">
                            @error('unitPrice')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Cost Price') }}</label>
                            <input wire:model="unitCostPrice" type="number" step="0.01" min="0"
                                class="mt-1.5 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                placeholder="{{ __('Cost') }}">
                            @error('unitCostPrice')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="flex gap-3 pt-1">
                    <button type="button" wire:click="save"
                        class="flex-1 rounded-xl bg-linear-to-r from-emerald-600 to-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-600/20 transition-all hover:from-emerald-700 hover:to-emerald-800 active:scale-[0.98]">
                        {{ $editingProductId ? __('Update') : __('Create') }}
                    </button>
                    <button type="button" wire:click="cancel"
                        class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        {{ __('Cancel') }}
                    </button>
                </div>
        @endif
        </form>
    </div>
    @endif

    {{-- Products List --}}
    <div class="space-y-3">
        @forelse ($products as $product)
        <div
            class="group rounded-2xl border border-gray-200/70 bg-white p-4 shadow-sm transition-all hover:border-emerald-200 hover:shadow-md dark:border-gray-700 dark:bg-gray-800">

            <div class="flex items-start justify-between gap-4">

                {{-- Left Content --}}
                <div class="flex min-w-0 flex-1 gap-4">

                    {{-- Avatar --}}
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-sm font-bold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                        {{ strtoupper(substr($product->name, 0, 2)) }}
                    </div>

                    {{-- Details --}}
                    <div class="min-w-0 flex-1">

                        {{-- Header --}}
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="truncate text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $product->name }}
                            </h3>

                            <span
                                class="rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                {{ $product->category->name }}
                            </span>
                        </div>

                        {{-- SKU --}}
                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            SKU: <span class="font-mono">{{ $product->sku }}</span>
                        </div>

                        {{-- Description --}}
                        @if ($product->description)
                        <p class="mt-2 line-clamp-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ $product->description }}
                        </p>
                        @endif

                        {{-- Units --}}
                        @if ($product->units->isNotEmpty())
                        <div class="mt-3 flex flex-wrap gap-1.5">
                            @foreach ($product->units as $unit)
                            <div class="group/unit relative">
                                <span
                                    class="inline-flex cursor-help items-center gap-1.5 rounded-lg bg-gray-100 px-2 py-1 text-[11px] font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    {{ $unit->name }}
                                    <span class="text-[10px] text-gray-400">({{ $unit->quantity }})</span>
                                    <span class="text-[10px] text-emerald-600">Ks {{ number_format($unit->price, 2)
                                        }}</span>
                                </span>
                                {{-- Tooltip --}}
                                <div
                                    class="invisible absolute bottom-full left-1/2 mb-2 z-10 w-48 -translate-x-1/2 rounded-lg bg-gray-900 px-3 py-2 text-xs text-white opacity-0 transition group-hover/unit:visible group-hover/unit:opacity-100 dark:bg-gray-800">
                                    <div class="font-medium">{{ __('Unit Details') }}</div>
                                    <div class="mt-1 space-y-0.5">
                                        <div>{{ __('SKU') }}: {{ $unit->sku }}</div>
                                        <div>{{ __('Quantity') }}: {{ $unit->quantity }}</div>
                                        <div>{{ __('Price') }}: Ks {{ number_format($unit->price, 2) }}</div>
                                    </div>
                                    <div
                                        class="absolute left-1/2 top-full -translate-x-1/2 -translate-y-1 border-4 border-transparent border-t-gray-900 dark:border-t-gray-800">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Right Side --}}
                <div class="flex shrink-0 flex-col items-end gap-3">

                    {{-- Actions --}}
                    <div class="flex items-center gap-1">
                        <button wire:click="edit({{ $product->id }})"
                            class="rounded-xl p-2 text-gray-400 transition hover:bg-gray-100 hover:text-emerald-600 dark:hover:bg-gray-700 dark:hover:text-emerald-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>

                        <button wire:click="confirmDelete({{ $product->id }})"
                            class="rounded-xl p-2 text-gray-400 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20 dark:hover:text-red-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>

                    {{-- Stock Card --}}
                    <div class="rounded-xl border px-3 py-2 text-center
                        {{ $product->stock < 10
                            ? 'border-red-200 bg-red-50 dark:border-red-900/30 dark:bg-red-900/10'
                            : 'border-emerald-200 bg-emerald-50 dark:border-emerald-900/30 dark:bg-emerald-900/10' }}">

                        <div class="text-lg font-bold
                            {{ $product->stock < 10
                                ? 'text-red-600 dark:text-red-400'
                                : 'text-emerald-600 dark:text-emerald-400' }}">
                            {{ $product->stock }}
                        </div>

                        <div class="text-[11px] text-gray-500 dark:text-gray-400">
                            {{ __('In Stock') }}
                        </div>
                    </div>

                    {{-- Units Count --}}
                    <div class="text-center">
                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $product->units_count }}
                        </div>
                        <div class="text-[11px] text-gray-500 dark:text-gray-400">
                            {{ __('Units') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @empty
        <div
            class="rounded-2xl border border-dashed border-gray-300 bg-white py-14 text-center dark:border-gray-700 dark:bg-gray-800/40">
            <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>

            <p class="mt-4 text-sm font-medium text-gray-500 dark:text-gray-400">
                @if(!empty($search))
                {{ __('No products found matching') }} "{{ $search }}"
                @else
                {{ __('No products yet. Click "New Product" to get started.') }}
                @endif
            </p>
        </div>
        @endforelse
    </div>

    {{-- Load More --}}
    @if($hasMorePages)
    <div class="flex justify-center pt-2">
        <button wire:click="loadMore" wire:loading.attr="disabled"
            class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-6 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 disabled:opacity-50 disabled:cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
            <span wire:loading.remove wire:target="loadMore">{{ __('Load More Products') }}</span>
            <span wire:loading wire:target="loadMore" class="inline-flex items-center gap-2">
                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                {{ __('Loading...') }}
            </span>
        </button>
    </div>
    @endif

    {{-- Delete Modal --}}
    @if ($deletingProductId)
    <div class="fixed inset-0 z-50 flex items-end justify-center sm:items-center">
        <div wire:click="cancelDelete" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative mx-4 w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-800 sm:mb-0">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="mt-4 text-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Delete Product?') }}</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('This will also delete all units under this product. This action cannot be undone.') }}
                </p>
            </div>
            <div class="mt-6 flex gap-3">
                    <button wire:click="cancelDelete"
                        class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        {{ __('Cancel') }}
                    </button>
                    <button wire:click="delete"
                        class="flex-1 rounded-xl bg-linear-to-r from-red-600 to-red-700 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 transition-all hover:from-red-700 hover:to-red-800 active:scale-[0.98]">
                        {{ __('Delete') }}
                    </button>
            </div>
        </div>
    </div>
    @endif
</div>
