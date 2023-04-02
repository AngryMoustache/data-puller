@props([
    'text' => $slot,
])

<a
    wire:loading.class="opacity-50 cursor-not-allowed"
    {{ $attributes->merge(['class' => '
        block bg-dark rounded px-4 py-2
        hover:bg-dark-hover cursor-pointer
    ']) }}
>
    {{ $text }}
</a>
