@props(['color' => 'emerald', 'size' => 'md'])

@php
    $colorMap = [
        'emerald' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-900/30 dark:text-emerald-400 dark:ring-emerald-400/20',
        'blue' => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-900/30 dark:text-blue-400 dark:ring-blue-400/20',
        'red' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-900/30 dark:text-red-400 dark:ring-red-400/20',
        'amber' => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-900/30 dark:text-amber-400 dark:ring-amber-400/20',
        'gray' => 'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-gray-900/30 dark:text-gray-400 dark:ring-gray-400/20',
    ];
    $sizeClass = $size === 'sm' ? 'px-1.5 py-0.5 text-[10px]' : 'px-2 py-0.5 text-[11px]';
    $colorClass = $colorMap[$color] ?? $colorMap['emerald'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-md {$sizeClass} font-semibold ring-1 ring-inset {$colorClass}"]) }}>
    {{ $slot }}
</span>
