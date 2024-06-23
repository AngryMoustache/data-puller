@props([
    'text' => $slot,
    'icon' => null,
])

<a {{ $attributes->except('text')->merge(['class' => '
    flex gap-2
    rounded border border-border px-3 py-1 inline-flex items-center
    hover:bg-surface cursor-pointer
    text-sm font-medium
']) }}>
    @if ($icon)
        <x-dynamic-component
            :component="$icon"
            class="w-4 h-4 text-text"
        />
    @endif

    {{ $text }}
</a>
