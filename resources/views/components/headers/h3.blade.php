@props([
    'text' => $slot,
])

<h3 {{ $attributes->merge([
    'class' => 'font-semibold text-md flex items-center',
]) }}>
    {{ $text }}
</h3>
