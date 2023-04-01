@props([
    'origin',
    'type' => $origin->type,
])

<span
    style="{{ $type->style() }}"
    {{ $attributes->only('class')->merge([
        'class' => 'inline-flex gap-2 items-center rounded px-2',
    ]) }}
>
    <i class="{{ $type->icon() }}"></i>
    {{ $origin->name }}
</span>
