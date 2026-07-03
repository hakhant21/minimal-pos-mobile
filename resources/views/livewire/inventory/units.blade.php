<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-linear-to-br from-amber-500 to-amber-600 shadow-lg shadow-amber-500/20">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Units') }}</h2>
        </div>
        <button wire:click="create"
            class="inline-flex items-center gap-1.5 rounded-xl bg-linear-to-r from-amber-600 to-amber-700 px-3.5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-600/20 transition-all hover:from-amber-700 hover:to-amber-800 hover:shadow-xl hover:shadow-amber-600/25 active:scale-[0.98]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('New Unit') }}
        </button>
    </div>

    {{-- Search Bar --}}
    <div class="relative">
        <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400 dark:text-gray-500" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('Search products or units...') }}"
            class="w-full rounded-xl border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm shadow-sm transition focus:border-amber-400 focus:ring-2 focus:ring-amber-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-amber-500">
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

    {{-- Products Grouped List --}}
    <div class="space-y-3">
        @forelse ($products as $product)
        <div
            class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm dark:border-gray-700/80 dark:bg-gray-800/80">
            {{-- Product Header (Clickable) --}}
            <button wire:click="toggleProduct({{ $product->id }})" class="w-full group">
                <div
                    class="flex items-center justify-between px-4 py-3 transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <div class="flex items-center gap-3 flex-1">
                        {{-- Product Icon --}}
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-sm font-bold text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                            {{ strtoupper(substr($product->name, 0, 2)) }}
                        </div>

                        {{-- Product Info --}}
                        <div class="flex-1 text-left">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $product->name }}
                                </h3>
                                <span
                                    class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                    {{ $product->units_count }} {{ $product->units_count == 1 ? __('unit') : __('units') }}
                                </span>
                            </div>
                            @if($product->category)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $product->category->name }}
                            </p>
                            @endif
                        </div>
                    </div>

                    {{-- Chevron Icon --}}
                    <div class="shrink-0 ml-4">
                        <svg class="h-5 w-5 text-gray-400 transition-transform duration-200 {{ in_array($product->id, $this->expandedProducts) ? 'rotate-180' : '' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </button>

            {{-- Units List (Expandable) --}}
            @if(in_array($product->id, $this->expandedProducts))
            <div class="border-t border-gray-100 dark:border-gray-700/50">
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($product->units as $unit)
                    <div
                        class="group flex items-center justify-between px-4 py-3 transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        {{-- Unit Info --}}
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-50 text-xs font-bold text-amber-600 dark:bg-amber-900/20 dark:text-amber-400">
                                {{ strtoupper(substr($unit->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $unit->name }}</p>
                                    <span class="text-xs text-gray-400">•</span>
                                    <span class="text-xs font-mono text-gray-500 dark:text-gray-400">{{ $unit->sku
                                        }}</span>
                                </div>
                                <div class="mt-0.5 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                    <span>{{ __('Qty') }}: {{ $unit->quantity }}</span>
                                    <span>•</span>
                                    <span class="font-semibold text-amber-600 dark:text-amber-400">
                                        Ks {{ number_format($unit->price, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-1 shrink-0">
                            <button wire:click="edit({{ $unit->id }})"
                                class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-amber-600 dark:hover:bg-gray-700 dark:hover:text-amber-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="confirmDelete({{ $unit->id }})"
                                class="rounded-lg p-1.5 text-gray-400 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20 dark:hover:text-red-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                        {{ __('No units found for this product.') }}
                    </div>
                    @endforelse

                    {{-- Add Unit Button --}}
                    <div class="border-t border-gray-100 px-4 py-2 dark:border-gray-700/50">
                        <button wire:click="create" wire:key="add-unit-{{ $product->id }}"
                            class="inline-flex items-center gap-1 text-xs text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300 transition-colors py-2">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            {{ __('Add Unit to') }} {{ $product->name }}
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @empty
        <div
            class="rounded-2xl border border-dashed border-gray-300 bg-white/50 px-4 py-12 text-center dark:border-gray-600 dark:bg-gray-800/30">
            <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
            <p class="mt-3 text-sm font-medium text-gray-500 dark:text-gray-400">
                @if(!empty($search))
                {{ __('No products or units found matching') }} "{{ $search }}"
                @else
                {{ __('No products yet. Create a product first to add units.') }}
                @endif
            </p>
        </div>
        @endforelse
    </div>

    {{-- Load More --}}
    @if($hasMorePages)
    <div class="flex justify-center pt-2">
        <button wire:click="loadMore" wire:loading.attr="disabled"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-500/20 disabled:opacity-50 disabled:cursor-not-allowed dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 sm:w-auto sm:px-6">
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

    {{-- Form Modal --}}
    @if ($showForm)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm">
        <div class="min-h-screen px-4 py-6 flex items-center justify-center">
            <div
                class="w-full max-w-md rounded-2xl border border-gray-200/80 bg-white p-5 shadow-xl dark:border-gray-700/80 dark:bg-gray-800/80">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $editingUnitId ? __('Edit Unit') : __('New Unit') }}
                    </h3>
                    <button wire:click="cancel"
                        class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save" class="space-y-4">
                    <div class="relative" x-data="{ open: false }" x-on:click.away="open = false">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Product') }}</label>
                        <input type="text" wire:model.live="productSearch" x-on:focus="open = true"
                            x-on:input="open = true" placeholder="{{ __('Search product...') }}"
                            class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm transition focus:border-amber-400 focus:ring-2 focus:ring-amber-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <div x-show="open" x-transition
                            class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto dark:bg-gray-800 dark:border-gray-700"
                            style="display: none;">
                            @if($allProducts && count($allProducts) > 0)
                            @foreach($allProducts as $product)
                            <div wire:click="selectUnitProduct({{ $product->id }})" x-on:click="open = false"
                                class="px-4 py-2.5 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ $product_id == $product->id ? 'bg-amber-50 dark:bg-amber-900/20' : '' }}">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name
                                        }}</span>
                                    <span class="text-xs text-gray-500">{{ $product->category->name ?? 'No category'
                                        }}</span>
                                </div>
                            </div>
                            @endforeach
                            @if($hasMoreProducts)
                            <div class="border-t p-2">
                                <button wire:click="loadMoreProducts"
                                    class="w-full text-center text-xs text-amber-600 py-1.5">
                                    {{ __('Load more products...') }}
                                </button>
                            </div>
                            @endif
                            @else
                            <div class="px-4 py-2.5 text-center text-sm text-gray-500">
                                {{ __('No products found') }}
                            </div>
                            @endif
                        </div>
                        @error('product_id')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Unit Name') }}</label>
                        <input wire:model="name" type="text"
                            class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm focus:border-amber-400 focus:ring-2 focus:ring-amber-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('e.g. Bottle, Case, Can') }}">
                        @error('name')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Quantity per Unit') }}</label>
                        <input wire:model="quantity" type="number" min="1"
                            class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm focus:border-amber-400 focus:ring-2 focus:ring-amber-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="{{ __('e.g., 12 (for a case of 12)') }}">
                        @error('quantity')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Price') }}</label>
                        <input wire:model="price" type="number" step="0.01" min="0"
                            class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm focus:border-amber-400 focus:ring-2 focus:ring-amber-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="0.00">
                        @error('price')
                        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                            class="flex-1 rounded-xl bg-linear-to-r from-amber-600 to-amber-700 px-4 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:from-amber-700 hover:to-amber-800 active:scale-[0.98]">
                            {{ $editingUnitId ? __('Update') : __('Create') }}
                        </button>
                        <button type="button" wire:click="cancel"
                            class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($deletingUnitId)
    <div class="fixed inset-0 z-50 flex items-end justify-center sm:items-center">
        <div wire:click="cancelDelete" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative mx-4 w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-800">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="mt-4 text-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Delete Unit?') }}</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Units with sale records cannot be deleted. This action cannot be undone.') }}
                </p>
            </div>
            <div class="mt-6 flex gap-3">
                <button wire:click="cancelDelete"
                    class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                    Cancel
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