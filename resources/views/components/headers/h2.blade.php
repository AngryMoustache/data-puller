@props([
    'text' => $slot,
])

<h1 {{ $attributes->merge([
    'class' => 'font-semibold text-lg',
]) }}>
    {{ $text }}
</h1>
