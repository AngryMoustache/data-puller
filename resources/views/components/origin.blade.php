@props([
    'origin',
    'label' => $origin->name,
    'type' => $origin->type,
    'sourceUrl' => null,
])

<span class="inline-flex items-center">
    <a
        style="{{ $type->style() }}"
        {{ $attributes->only(['class', 'href'])->merge([
            'class' => 'inline-flex gap-2 items-center rounded px-2',
        ]) }}
    >
        <i class="{{ $type->icon() }}"></i>
        {{ $label }}
    </a>

    @if ($sourceUrl)
        <a href="{{ $sourceUrl }}" target="_blank">
            <x-heroicon-o-link class="w-4 h-4" />
        </a>
    @endif
</span>
