<div class="space-y-6">
    <div class="flex items-center gap-3">
        <div
            class="flex h-10 w-10 items-center justify-center rounded-xl bg-linear-to-br from-amber-500 to-amber-600 shadow-lg shadow-amber-500/20">
            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Sales History') }}</h2>
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

    @forelse ($sales as $sale)
    <div
        class="overflow-hidden rounded-xl border border-gray-200/80 bg-white shadow-sm transition-all hover:shadow-md dark:border-gray-700/80 dark:bg-gray-800/80">
        <button wire:click="toggleSale({{ $sale->id }})"
            class="flex w-full items-center gap-3 px-4 py-3.5 text-left transition hover:bg-gray-50/80 dark:hover:bg-gray-800/50">
            <div
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-sm font-bold text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                {{ $sale->invoice_number }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $sale->created_at->format('M d, Y h:i A')
                        }}
                        <span class="ml-1 text-gray-400">• {{ $sale->payment_method }}</span>
                    </span>
                    <span class="text-base font-bold text-gray-900 dark:text-white">Ks {{
                        number_format($sale->total, 2) }}</span>
                </div>
                @if ($sale->notes)
                <p class="mt-0.5 truncate text-xs text-gray-400 dark:text-gray-500">{{ $sale->notes }}</p>
                @endif
            </div>
            <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200 {{ $expandedSaleId === $sale->id ? 'rotate-180' : '' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        @if ($expandedSaleId === $sale->id)
        <div class="border-t border-gray-100 dark:border-gray-700/50">
            <div class="divide-y divide-gray-50 dark:divide-gray-800/50">
                @foreach ($sale->items as $item)
                <div
                    class="flex items-center justify-between px-4 py-2.5 pl-12 transition hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->variant->product->name ??
                            __('N/A') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $item->quantity }} × {{ $item->variant->unit->name ?? __('N/A') }}
                            <span class="text-gray-300 dark:text-gray-600">@</span>
                            Ks {{ number_format($item->unit_price, 2) }}
                        </p>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Ks {{
                        number_format($item->total_price,
                        2) }}</span>
                </div>
                @endforeach
            </div>
            <div class="border-t border-gray-100 bg-gray-50/80 px-4 py-2 dark:border-gray-700/50 dark:bg-gray-800/50">
                <button wire:click="confirmDelete({{ $sale->id }})"
                    class="flex items-center gap-1.5 text-xs font-medium text-red-500 transition hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    {{ __('Delete sale') }}
                </button>
            </div>
        </div>
        @endif
    </div>
    @empty
    <div
        class="rounded-xl border border-dashed border-gray-300 bg-white/50 px-4 py-12 text-center dark:border-gray-600 dark:bg-gray-800/30">
        <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z" />
        </svg>
        <p class="mt-3 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('No sales yet') }}</p>
        <a href="/sales"
            class="mt-2 inline-flex items-center gap-1 text-sm font-semibold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            {{ __('Create your first sale') }}
        </a>
    </div>
    @endforelse

    <div
        class="flex items-center justify-between gap-1 rounded-xl border border-gray-200/80 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-900 shadow-sm dark:border-gray-700/80 dark:bg-gray-800/80 dark:text-white">
        <div>
            <span class="text-gray-500 dark:text-gray-400 text-xl">{{ __('Total Revenue:') }}</span>
        </div>
        <span class="text-gray-500 dark:text-gray-400 font-bold text-xl">
            Ks {{ number_format($sales->sum('total'), 2) }}
        </span>
    </div>

    @if ($deleteSaleId)
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
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Delete Sale?') }}</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('This will restore all stock for the
                    items in this sale. This action cannot be undone.') }}</p>
            </div>
            <div class="mt-6 flex gap-3">
                <button wire:click="cancelDelete"
                    class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    {{ __('Cancel') }}
                </button>
                <button wire:click="deleteSale"
                    class="flex-1 rounded-xl bg-linear-to-r from-red-600 to-red-700 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 transition-all hover:from-red-700 hover:to-red-800 active:scale-[0.98]">
                    {{ __('Delete') }}
                </button>
            </div>
        </div>
    </div>
    @endif

    @if ($sales->hasPages())
    <div
        class="rounded-xl border border-gray-200/80 bg-white px-4 py-3 shadow-sm dark:border-gray-700/80 dark:bg-gray-800/80">
        {{ $sales->links() }}
    </div>
    @endif
</div>