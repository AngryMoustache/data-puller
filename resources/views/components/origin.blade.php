@props([
    'origin',
    'type' => $origin->type,
])

<span
    class="inline-flex gap-2 items-center rounded px-2"
    style="{{ $type->style() }}"
>
    <i class="{{ $type->icon() }}"></i>
    {{ $origin->name }}
</span>
