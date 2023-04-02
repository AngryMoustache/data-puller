@props([
    'origin',
    'type' => $origin->type,
])

<a
    style="{{ $type->style() }}"
    target="_blank"
    {{ $attributes->only(['class', 'href'])->merge([
        'class' => 'inline-flex gap-2 items-center rounded px-2',
    ]) }}
>
    <i class="{{ $type->icon() }}"></i>
    {{ $origin->name }}
</a>
