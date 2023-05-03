@props([
    'text' => $slot,
])

<h1 {{ $attributes->merge([
    'class' => 'font-semibold text-md flex gap-8 items-center',
]) }}>
    <span>{{ $text }}</span>
</h1>
