@props([
    'origin',
    'label' => $origin->name,
    'type' => $origin->type,
])

<a
    style="{{ $type->style() }}"
    {{ $attributes->only(['class', 'href'])->merge([
        'class' => 'inline-flex gap-2 items-center rounded px-2',
    ]) }}
>
    <i class="{{ $type->icon() }}"></i>
    {{ $label }}
</a>
