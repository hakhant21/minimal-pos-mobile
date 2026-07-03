<div class="space-y-6">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div
                class="flex h-10 w-10 items-center justify-center rounded-xl bg-linear-to-br from-violet-500 to-violet-600 shadow-lg shadow-violet-500/20">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Profit & Loss') }}</h2>
        </div>
        <select wire:model.live="selectedMonth"
            class="rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-violet-400 focus:ring-2 focus:ring-violet-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            @foreach ($allMonths as $month)
            <option value="{{ $month }}">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        <div class="rounded-2xl border border-gray-200/80 bg-white p-4 dark:border-gray-700/80 dark:bg-gray-800/80">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('Revenue') }}</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">Ks {{ number_format($summary['total_revenue'], 2) }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200/80 bg-white p-4 dark:border-gray-700/80 dark:bg-gray-800/80">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('COGS') }}</p>
            <p class="mt-1 text-2xl font-bold text-amber-600 dark:text-amber-400">Ks {{ number_format($summary['total_cogs'], 2) }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200/80 bg-white p-4 dark:border-gray-700/80 dark:bg-gray-800/80">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('Gross Profit') }}</p>
            <p class="mt-1 text-2xl font-bold {{ $summary['total_profit'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                Ks {{ number_format($summary['total_profit'], 2) }}
            </p>
        </div>
        <div class="rounded-2xl border border-gray-200/80 bg-white p-4 dark:border-gray-700/80 dark:bg-gray-800/80">
            <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('Margin') }}</p>
            <p class="mt-1 text-2xl font-bold {{ $summary['margin'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                {{ $summary['margin'] }}%
            </p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm dark:border-gray-700/80 dark:bg-gray-800/80">
        <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-700/50">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Product Breakdown') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('Product') }}</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('Sold') }}</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('Revenue') }}</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('COGS') }}</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('Profit') }}</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('Margin') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($productRows as $row)
                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-4 py-2.5">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $row['name'] }}</span>
                        </td>
                        <td class="px-4 py-2.5 text-right text-gray-700 dark:text-gray-300">{{ $row['total_qty'] }}</td>
                        <td class="px-4 py-2.5 text-right font-mono text-gray-900 dark:text-white">Ks {{ number_format($row['revenue'], 2) }}</td>
                        <td class="px-4 py-2.5 text-right font-mono text-amber-600 dark:text-amber-400">Ks {{ number_format($row['cogs'], 2) }}</td>
                        <td class="px-4 py-2.5 text-right font-mono {{ $row['profit'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                            Ks {{ number_format($row['profit'], 2) }}
                        </td>
                        <td class="px-4 py-2.5 text-right">
                            <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold
                                {{ $row['margin'] >= 20 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : ($row['margin'] >= 0 ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400') }}">
                                {{ $row['margin'] }}%
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center">
                            <svg class="mx-auto h-8 w-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ $summary['total_revenue'] > 0 ? __('No product breakdown available.') : __('No sales data for this month.') }}
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
