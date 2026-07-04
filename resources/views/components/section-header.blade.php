@props(['title', 'color' => 'emerald', 'icon' => null, 'size' => 'default'])

<div class="flex items-center gap-3">
    <div class="flex {{ $size === 'sm' ? 'h-7 w-7 rounded-lg' : 'h-10 w-10 rounded-xl' }} items-center justify-center bg-linear-to-br from-{{ $color }}-500 to-{{ $color }}-600 shadow-lg shadow-{{ $color }}-500/20">
        @if($icon)
            <svg class="{{ $size === 'sm' ? 'h-3.5 w-3.5' : 'h-5 w-5' }} text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
            </svg>
        @else
            {{ $slot }}
        @endif
    </div>
    <h{{ $size === 'sm' ? '3' : '2' }} class="{{ $size === 'sm' ? 'text-sm font-semibold' : 'text-xl font-bold' }} text-gray-900 dark:text-white">{{ $title }}</h{{ $size === 'sm' ? '3' : '2' }}>
</div>
