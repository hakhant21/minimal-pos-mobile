<div class="space-y-6">
    <div class="flex justify-between">
        <x-section-header title="{{ __('Sale Detail') }}" color="amber"
            icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        <div>
            <a href="{{ route('history') }}"
                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-gray-50 px-3 py-1.5 text-sm font-medium text-gray-700 shadow-xs transition hover:bg-gray-100 hover:text-gray-900 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ __('Go Back') }}
            </a>
        </div>
    </div>


    <x-card padding="p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Invoice Number') }}</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $sale->invoice_number }}</p>
            </div>
            <x-badge color="emerald">{{ $sale->payment_method }}</x-badge>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500 dark:text-gray-400">{{ __('Date') }}</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $sale->created_at->format('M d, Y h:i A') }}</p>
            </div>
            @if ($sale->customer_name)
            <div>
                <p class="text-gray-500 dark:text-gray-400">{{ __('Customer') }}</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $sale->customer_name }}</p>
            </div>
            @endif
        </div>

        @if ($sale->notes)
        <div class="mt-3 text-sm">
            <p class="text-gray-500 dark:text-gray-400">{{ __('Notes') }}</p>
            <p class="font-medium text-gray-900 dark:text-white">{{ $sale->notes }}</p>
        </div>
        @endif
    </x-card>

    <x-card padding="p-0">
        <x-slot:header>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Items') }}</h3>
        </x-slot:header>

        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse ($sale->items as $item)
            <div class="flex items-center justify-between px-5 py-3">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->variant->product->name ??
                        __('N/A') }}</p>
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
            @empty
            <div class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                {{ __('No items found for this sale.') }}
            </div>
            @endforelse
        </div>
    </x-card>

    <x-card padding="p-5">
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">{{ __('Subtotal') }}</span>
                <span class="text-gray-900 dark:text-white">Ks {{ number_format($sale->subtotal, 2) }}</span>
            </div>
            @if ((float) $sale->discount > 0)
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">{{ __('Discount') }}</span>
                <span class="text-red-500">-Ks {{ number_format($sale->discount, 2) }}</span>
            </div>
            @endif
            @if ((float) $sale->tax > 0)
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">{{ __('Tax') }}</span>
                <span class="text-gray-900 dark:text-white">Ks {{ number_format($sale->tax, 2) }}</span>
            </div>
            @endif
            <div class="border-t border-gray-200 pt-2 dark:border-gray-700">
                <div class="flex justify-between text-base">
                    <span class="font-semibold text-gray-900 dark:text-white">{{ __('Total') }}</span>
                    <span class="font-bold text-amber-600 dark:text-amber-400">Ks {{ number_format($sale->total, 2)
                        }}</span>
                </div>
            </div>
            @if ((float) $sale->amount_paid > 0)
            <div class="flex justify-between pt-1">
                <span class="text-gray-500 dark:text-gray-400">{{ __('Amount Paid') }}</span>
                <span class="font-medium text-emerald-600 dark:text-emerald-400">Ks {{ number_format($sale->amount_paid,
                    2) }}</span>
            </div>
            @endif
            @if ((float) $sale->change_amount > 0)
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">{{ __('Change') }}</span>
                <span class="font-medium text-gray-900 dark:text-white">Ks {{ number_format($sale->change_amount, 2)
                    }}</span>
            </div>
            @endif
        </div>
    </x-card>
</div>
