@props([
    'text' => $slot,
])

<button {{ $attributes->merge([
    'class' => 'bg-dark rounded px-4 py-2',
]) }}>
    {{ $text }}
</button>
