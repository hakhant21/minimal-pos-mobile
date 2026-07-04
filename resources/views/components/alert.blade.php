@props(['type' => 'success', 'message' => null, 'dismissible' => false])

@php
    $config = match($type) {
        'success' => ['border' => 'border-emerald-200/80', 'from' => 'from-emerald-50', 'to' => 'to-green-50', 'text' => 'text-emerald-800', 'icon' => 'text-emerald-600', 'darkBorder' => 'dark:border-emerald-800/50', 'darkFrom' => 'dark:from-emerald-950/30', 'darkTo' => 'dark:to-green-950/20', 'darkText' => 'dark:text-emerald-300', 'darkIcon' => 'dark:text-emerald-400', 'path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        'error' => ['border' => 'border-red-200/80', 'from' => 'from-red-50', 'to' => 'to-orange-50', 'text' => 'text-red-800', 'icon' => 'text-red-600', 'darkBorder' => 'dark:border-red-800/50', 'darkFrom' => 'dark:from-red-950/30', 'darkTo' => 'dark:to-orange-950/20', 'darkText' => 'dark:text-red-300', 'darkIcon' => 'dark:text-red-400', 'path' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        default => ['border' => 'border-gray-200/80', 'from' => 'from-gray-50', 'to' => 'to-gray-50', 'text' => 'text-gray-800', 'icon' => 'text-gray-600', 'darkBorder' => 'dark:border-gray-800/50', 'darkFrom' => 'dark:from-gray-950/30', 'darkTo' => 'dark:to-gray-950/20', 'darkText' => 'dark:text-gray-300', 'darkIcon' => 'dark:text-gray-400', 'path' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    };
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-2 rounded-xl border ' . $config['border'] . ' bg-linear-to-r ' . $config['from'] . ' ' . $config['to'] . ' px-4 py-3 text-sm font-medium ' . $config['text'] . ' shadow-sm ' . $config['darkBorder'] . ' ' . $config['darkFrom'] . ' ' . $config['darkTo'] . ' ' . $config['darkText']]) }}>
    <svg class="h-4 w-4 shrink-0 {{ $config['icon'] }} {{ $config['darkIcon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['path'] }}" />
    </svg>
    <span class="flex-1">{{ $message ?? $slot }}</span>
    @if($dismissible)
        <button type="button" onclick="this.parentElement.remove()" class="shrink-0 opacity-60 hover:opacity-100 transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>
