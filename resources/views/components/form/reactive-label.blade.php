@props([
    'value' => '',
    'label' => null,
])

<div
    class="relative w-full"
    x-data="{ value: @js($value), activated: @js(! empty($value)) }"
>
    @if ($label)
        <label
            class="absolute top-0.5 left-0 pointer-events-none transition-all px-4 py-2"
            :class="{ '!-top-4 text-xs' : activated }"
        >
            {{ $label }}
        </label>
    @endif

    {{ $slot }}
</div>
