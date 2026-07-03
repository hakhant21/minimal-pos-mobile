<div class="space-y-6">
    <div class="grid grid-cols-2 gap-3">
        <div
            class="group relative overflow-hidden rounded-2xl border border-gray-200/80 bg-white p-4 shadow-sm transition-all hover:shadow-md dark:border-gray-700/80 dark:bg-gray-800/80">
            <div
                class="absolute -right-3 -top-3 h-16 w-16 rounded-full {{ $todaySales > 0 ? 'bg-amber-50 dark:bg-amber-900/20' : 'bg-gray-50 dark:bg-gray-700/50' }}">
            </div>
            <div class="relative">
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-lg {{ $todaySales > 0 ? 'bg-amber-100 dark:bg-amber-900/30' : 'bg-gray-100 dark:bg-gray-700' }}">
                    <svg class="h-4 w-4 {{ $todaySales > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-500 dark:text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z" />
                    </svg>
                </div>
                <p class="mt-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">{{
                    __('Today Sales') }}</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $todaySales }}</p>
                @if (isset($todayRevenue) && $todayRevenue > 0)
                <p class="mt-0.5 text-xs font-medium text-amber-600 dark:text-amber-400">Ks
                    {{ number_format($todayRevenue, 2) }}</p>
                @endif
            </div>
        </div>

        <div
            class="group relative overflow-hidden rounded-2xl border border-gray-200/80 bg-white p-4 shadow-sm transition-all hover:shadow-md dark:border-gray-700/80 dark:bg-gray-800/80">
            <div class="absolute -right-3 -top-3 h-16 w-16 rounded-full bg-emerald-50 dark:bg-emerald-900/20"></div>
            <div class="relative">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/30">
                    <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <p class="mt-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">{{
                    __('Total Stock') }}</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalStock) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div
            class="group relative overflow-hidden rounded-2xl border border-gray-200/80 bg-white p-4 shadow-sm transition-all hover:shadow-md dark:border-gray-700/80 dark:bg-gray-800/80">
            <div class="absolute -right-3 -top-3 h-16 w-16 rounded-full bg-violet-50 dark:bg-violet-900/20"></div>
            <div class="relative">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900/30">
                    <svg class="h-4 w-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="mt-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">{{
                    __('Inventory Value') }}</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    Ks {{ number_format($inventoryValue, 2) }}</p>
            </div>
        </div>

        <div
            class="group relative overflow-hidden rounded-2xl border border-gray-200/80 bg-white p-4 shadow-sm transition-all hover:shadow-md dark:border-gray-700/80 dark:bg-gray-800/80">
            <div
                class="absolute -right-3 -top-3 h-16 w-16 rounded-full {{ $todayRevenue > 0 ? 'bg-amber-50 dark:bg-amber-900/20' : 'bg-gray-50 dark:bg-gray-700/50' }}">
            </div>
            <div class="relative">
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-lg {{ $todayRevenue > 0 ? 'bg-amber-100 dark:bg-amber-900/30' : 'bg-gray-100 dark:bg-gray-700' }}">
                    <svg class="h-4 w-4 {{ $todayRevenue > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-500 dark:text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="mt-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">{{
                    __('Today Revenue') }}</p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    Ks {{ number_format($todayRevenue, 2) }}</p>
            </div>
        </div>
    </div>

    <div
        class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm dark:border-gray-700/80 dark:bg-gray-800/80">
        <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-700/50">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Low Stock Products') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th
                            class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ __('Product') }}</th>
                        <th
                            class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ __('Variant') }}</th>
                        <th
                            class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ __('Stock') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($lowStockVariants as $v)
                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-4 py-2.5">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $v->product->name }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">({{ $v->product->category->name
                                }})</span>
                        </td>
                        <td class="px-4 py-2.5 text-right text-gray-600 dark:text-gray-400">
                            {{ $v->unit->name }}
                        </td>
                        <td class="px-4 py-2.5 text-right">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-bold text-red-700 dark:bg-red-900/50 dark:text-red-400">
                                {{ $v->stock_quantity }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center">
                            <svg class="mx-auto h-8 w-8 text-gray-300 dark:text-gray-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('All products are well
                                stocked.') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div
        class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm dark:border-gray-700/80 dark:bg-gray-800/80">
        <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-700/50">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Recent Sales') }}</h3>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse ($recentSales as $sale)
            <div class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                <button wire:click="toggleRecentSale({{ $sale->id }})" wire:key="sale-{{ $sale->id }}-button"
                    class="w-full group focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-inset">
                    <div class="flex items-center justify-between px-4 py-3">
                        <div class="flex items-center gap-3 flex-1">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-xs font-bold text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                {{ $sale->items_count }}
                            </div>
                            <div class="flex-1 text-left">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    Ks {{ number_format($sale->total, 2) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $sale->created_at->format('M d, h:i A') }}
                                    <span class="ml-1 text-gray-400">• {{ $sale->invoice_number }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="shrink-0 ml-4">
                            <svg class="h-5 w-5 text-gray-400 transition-transform duration-200 {{ in_array($sale->id, $expandedSales) ? 'rotate-180' : '' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </button>

                @if(in_array($sale->id, $expandedSales))
                <div wire:key="sale-{{ $sale->id }}-items"
                    class="border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-900/30">
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($sale->items as $item)
                        <div class="flex items-center justify-between px-4 py-3 pl-11">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->variant->product->name ?? __('N/A') }}
                                </p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ __('Qty') }}: {{ $item->quantity }}
                                    </span>
                                    <span class="text-xs text-gray-400">•</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $item->variant->unit->name ?? __('N/A') }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-amber-600 dark:text-amber-400">
                                    Ks {{ number_format($item->total_price, 2) }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    Ks {{ number_format($item->unit_price, 2) }} / {{ __('Unit') }}
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                            {{ __('No items found for this sale.') }}
                        </div>
                        @endforelse
                    </div>
                </div>
                @endif
            </div>
            @empty
            <div class="px-4 py-8 text-center">
                <svg class="mx-auto h-8 w-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z" />
                </svg>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('No sales yet.') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>