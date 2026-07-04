<div class="space-y-6">
    <x-section-header title="{{ __('Sales History') }}" color="amber"
        icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />

    @if (session('message'))
    <x-alert type="success" :message="session('message')" />
    @endif

    @forelse ($sales as $sale)
    <x-card padding="p-0">
        <button wire:click="toggleSale({{ $sale->id }})"
            class="flex w-full items-start justify-between gap-4 px-5 py-4 text-left transition hover:bg-gray-50/80 dark:hover:bg-gray-800/50">
            <div class="flex-1 min-w-0">
                <p class="text-base font-bold text-gray-900 dark:text-white">{{ $sale->invoice_number }}</p>
                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    {{ $sale->created_at->format('M d, Y h:i A') }}
                    @if ($sale->customer_name)
                    <span class="text-gray-300 dark:text-gray-600">•</span>
                    <span>{{ $sale->customer_name }}</span>
                    @endif
                    <span class="text-gray-300 dark:text-gray-600">•</span>
                    <x-badge color="emerald" size="sm">{{ $sale->payment_method }}</x-badge>
                </div>
                <div class="mt-2">
                    <span class="text-lg font-bold text-gray-900 dark:text-white">Ks {{ number_format($sale->total, 2) }}</span>
                </div>
                @if ($sale->notes)
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ $sale->notes }}</p>
                @endif
            </div>
            <svg class="mt-1.5 h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200 {{ $expandedSaleId === $sale->id ? 'rotate-180' : '' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        @if ($expandedSaleId === $sale->id)
        <div class="border-t border-gray-100 dark:border-gray-700/50">
            <div class="divide-y divide-gray-50 dark:divide-gray-800/50">
                @foreach ($sale->items as $item)
                <div class="flex items-center justify-between px-5 py-3 pl-14 transition hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->variant->product->name ?? __('N/A') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $item->quantity }} × {{ $item->variant->unit->name ?? __('N/A') }}
                            <span class="text-gray-300 dark:text-gray-600">@</span>
                            Ks {{ number_format($item->unit_price, 2) }}
                        </p>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                        Ks {{ number_format($item->total_price, 2) }}
                    </span>
                </div>
                @endforeach
            </div>
            <div class="border-t border-gray-100 bg-gray-50/80 px-5 py-3 dark:border-gray-700/50 dark:bg-gray-800/50">
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $sale->items->count() }} {{ __('items') }}
                </span>
            </div>
        </div>
        @endif
    </x-card>
    @empty
    <x-card padding="p-0">
        <div class="px-4 py-12 text-center">
            <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z" />
            </svg>
            <p class="mt-3 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('No sales yet') }}</p>
            <a href="/sales" class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('Create your first sale') }}
            </a>
        </div>
    </x-card>
    @endforelse

    <div class="flex items-center justify-between gap-4 rounded-xl border border-gray-200/80 bg-white px-5 py-4 shadow-sm dark:border-gray-700/80 dark:bg-gray-800/80">
        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Revenue:') }}</span>
        <span class="text-base font-bold text-gray-900 dark:text-white">
            Ks {{ number_format($sales->sum('total'), 2) }}
        </span>
    </div>

    @if ($sales->hasPages())
    <x-card padding="p-0">
        <div class="px-5 py-4">
            {{ $sales->links() }}
        </div>
    </x-card>
    @endif
</div>
