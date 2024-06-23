@props([
    'text' => $slot,
])

<h1 {{ $attributes->merge([
    'class' => 'font-semibold text-2xl',
]) }}>
    {{ $text }}
</h1>
