@props([
'hasMorePages' => false,
'target' => 'loadMore',
'buttonText' => 'Load More',
'loadingText' => 'Loading...',
'color' => 'indigo',
'customClass' => null
])

@php
$colorClasses = match($color) {
'indigo' => 'border-indigo-300 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:border-indigo-800
dark:bg-indigo-950/30 dark:text-indigo-400 dark:hover:bg-indigo-950/50 focus:ring-indigo-500/20',
'emerald' => 'border-emerald-300 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:border-emerald-800
dark:bg-emerald-950/30 dark:text-emerald-400 dark:hover:bg-emerald-950/50 focus:ring-emerald-500/20',
'blue' => 'border-blue-300 bg-blue-50 text-blue-700 hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-950/30
dark:text-blue-400 dark:hover:bg-blue-950/50 focus:ring-blue-500/20',
'amber' => 'border-amber-300 bg-amber-50 text-amber-700 hover:bg-amber-100 dark:border-amber-800 dark:bg-amber-950/30
dark:text-amber-400 dark:hover:bg-amber-950/50 focus:ring-amber-500/20',
'red' => 'border-red-300 bg-red-50 text-red-700 hover:bg-red-100 dark:border-red-800 dark:bg-red-950/30
dark:text-red-400 dark:hover:bg-red-950/50 focus:ring-red-500/20',
'gray' => 'border-gray-300 bg-gray-50 text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800
dark:text-gray-300 dark:hover:bg-gray-700 focus:ring-gray-500/20',
default => 'border-indigo-300 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:border-indigo-800
dark:bg-indigo-950/30 dark:text-indigo-400 dark:hover:bg-indigo-950/50 focus:ring-indigo-500/20',
};
@endphp

@if($hasMorePages)
<div class="flex justify-center pt-4 {{ $customClass }}">
    <button wire:click="{{ $target }}" wire:loading.attr="disabled" wire:target="{{ $target }}"
        class="inline-flex items-center gap-2 rounded-xl border px-6 py-2.5 text-sm font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed {{ $colorClasses }}">

        <span wire:loading.remove wire:target="{{ $target }}">
            {{ $buttonText }}
        </span>

        <span wire:loading wire:target="{{ $target }}" class="inline-flex items-center gap-2">
            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            {{ $loadingText }}
        </span>
    </button>
</div>
@endif