@props([
    'wireDecrement',
    'wireIncrement',
    'size' => 'md',
    'model' => null,
    'quantity' => null,
    'showLabel' => false,
])

@php
    $btnClass = 'flex items-center justify-center rounded-lg border border-gray-300 bg-white text-gray-700 transition hover:bg-gray-50 hover:border-emerald-400 disabled:opacity-50 disabled:cursor-not-allowed dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600';
    $btnSize = $size === 'sm' ? 'h-8 w-8' : 'h-10 w-10';
    $iconSize = $size === 'sm' ? 'h-3.5 w-3.5' : 'h-4 w-4';
@endphp

<div class="flex items-center gap-2">
    <button type="button" wire:click="{{ $wireDecrement }}" wire:loading.attr="disabled"
        class="{{ $btnClass }} {{ $btnSize }}">
        <svg class="{{ $iconSize }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
        </svg>
    </button>

    @if($model)
        <input wire:model="{{ $model }}" type="number" min="1"
            class="block w-24 text-center rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-emerald-500"
            readonly>
    @elseif($quantity !== null)
        <span class="w-8 text-center text-sm font-semibold text-gray-900 dark:text-white">
            {{ $quantity }}
        </span>
    @endif

    <button type="button" wire:click="{{ $wireIncrement }}" wire:loading.attr="disabled"
        class="{{ $btnClass }} {{ $btnSize }}">
        <svg class="{{ $iconSize }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </button>

    @if($showLabel)
        <span class="text-xs text-gray-400 dark:text-gray-500">{{ __('Qty') }}</span>
    @endif
</div>
