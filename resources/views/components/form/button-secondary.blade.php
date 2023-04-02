@props([
    'text' => $slot,
])

<x-form.button {{ $attributes->merge(['class' => '
    bg-transparent text-primary border border-primary
    hover:bg-primary hover:text-white
']) }}>
    {!! $text !!}
</x-form.button>
