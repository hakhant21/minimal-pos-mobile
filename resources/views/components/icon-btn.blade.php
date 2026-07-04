@props(['wire' => null, 'color' => 'red', 'size' => 'sm'])

@php
    $sizeClass = $size === 'sm' ? 'h-8 w-8' : 'h-10 w-10';
    $iconSize = $size === 'sm' ? 'h-4 w-4' : 'h-5 w-5';
    $colorMap = [
        'red' => 'text-red-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400',
        'gray' => 'text-gray-400 hover:bg-gray-50 hover:text-gray-600 dark:hover:bg-gray-700 dark:hover:text-gray-300',
        'emerald' => 'text-emerald-400 hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-900/30 dark:hover:text-emerald-400',
    ];
    $colorClass = $colorMap[$color] ?? $colorMap['red'];
@endphp

<button type="button" @if($wire) wire:click="{{ $wire }}" @endif
    {{ $attributes->merge(['class' => "flex {$sizeClass} items-center justify-center rounded-lg transition {$colorClass}"]) }}>
    {{ $slot }}
</button>
