@props(['label', 'name' => null, 'optional' => false, 'labelClass' => null])

<div>
    @if($label)
        <label @if($name) for="{{ $name }}" @endif
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 {{ $labelClass ?? 'mb-1.5' }}">
            {{ $label }}
            @if($optional)
                <span class="text-xs font-normal text-gray-500">{{ __('(optional)') }}</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if($name)
        @error($name)
            <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
        @enderror
    @endif
</div>
