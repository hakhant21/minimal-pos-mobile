@props([
    'options',
    'model',
    'selected',
    'color' => 'emerald',
    'columns' => 2,
])

<div class="grid grid-cols-{{ $columns }} gap-3">
    @foreach($options as $value => $option)
        @php
            $isActive = $selected === $value;
            $activeBorder = "border-{$color}-500 bg-{$color}-50 dark:bg-{$color}-900/20 dark:border-{$color}-600";
            $activeText = "text-{$color}-800 dark:text-{$color}-300";
            $activeSub = "text-{$color}-600 dark:text-{$color}-400";
        @endphp
        <label wire:click="$set('{{ $model }}', '{{ $value }}')"
            class="flex items-center justify-center gap-2 rounded-xl border-2 p-3 cursor-pointer transition-all {{ $isActive ? $activeBorder : 'border-gray-200 hover:border-gray-300 dark:border-gray-700 dark:hover:border-gray-600' }}">
            @if(isset($option['icon']))
                <svg class="h-5 w-5 {{ $isActive ? $activeText : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $option['icon'] }}" />
                </svg>
            @endif
            <div class="text-center">
                <span class="text-sm font-medium {{ $isActive ? $activeText : 'text-gray-600 dark:text-gray-400' }}">
                    {{ $option['label'] }}
                </span>
                @if(isset($option['subtitle']))
                    <span class="block text-xs {{ $isActive ? $activeSub : 'text-gray-400 dark:text-gray-500' }}">
                        {{ $option['subtitle'] }}
                    </span>
                @endif
            </div>
        </label>
    @endforeach
</div>
