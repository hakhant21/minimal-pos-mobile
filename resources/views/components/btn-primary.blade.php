@props(['color' => 'emerald', 'size' => 'md', 'fullWidth' => true, 'wire' => null])

@php
    $colorMap = [
        'emerald' => 'from-emerald-500 to-emerald-600 shadow-emerald-500/20 hover:from-emerald-600 hover:to-emerald-700 hover:shadow-emerald-500/25',
        'blue' => 'from-blue-600 to-blue-700 shadow-blue-600/20 hover:from-blue-700 hover:to-blue-800 hover:shadow-blue-600/25',
        'red' => 'from-red-500 to-red-600 shadow-red-500/20 hover:from-red-600 hover:to-red-700 hover:shadow-red-500/25',
        'amber' => 'from-amber-500 to-amber-600 shadow-amber-500/20 hover:from-amber-600 hover:to-amber-700 hover:shadow-amber-500/25',
        'gray' => 'from-gray-500 to-gray-600 shadow-gray-500/20 hover:from-gray-600 hover:to-gray-700 hover:shadow-gray-500/25',
    ];
    $colorClass = $colorMap[$color] ?? $colorMap['emerald'];
    $widthClass = $fullWidth ? 'w-full' : '';
    $sizeClass = $size === 'lg' ? 'px-4 py-3.5' : 'px-4 py-2.5';
@endphp

<button type="button" @if($wire) wire:click="{{ $wire }}" @endif
    {{ $attributes->merge(['class' => "flex {$widthClass} items-center justify-center gap-2 rounded-xl bg-linear-to-r {$colorClass} {$sizeClass} text-sm font-semibold text-white shadow-lg transition-all hover:shadow-xl active:scale-[0.98]"]) }}>
    {{ $slot }}
</button>
