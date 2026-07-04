@props(['padding' => 'p-5', 'header' => null])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-gray-200/80 bg-white shadow-sm dark:border-gray-700/80 dark:bg-gray-800/80']) }}>
    @if($header)
        <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/80 px-4 py-3 dark:border-gray-700/50 dark:bg-gray-800/50">
            {{ $header }}
        </div>
    @endif
    <div class="{{ $padding }}">
        {{ $slot }}
    </div>
</div>
