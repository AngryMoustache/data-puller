@props([
    'text' => $slot,
])

<a {{ $attributes->except('text')->merge(['class' => '
    rounded border border-border px-3 py-1 inline-flex items-center
    hover:bg-surface cursor-pointer
    text-sm font-medium
']) }}>
    {{ $text }}
</a>
