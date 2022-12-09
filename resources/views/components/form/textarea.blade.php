@props([
    'label' => '',
    'value' => null,
])

<x-form.reactive-label :$label>
    <textarea
        data-label-target
        x-on:focus="setLabelStatus(true)"
        x-on:blur="setLabelStatus()"
        {{ $attributes->merge([
            'class' => 'bg-background px-4 py-2 text-lg rounded-lg w-full outline-none',
        ]) }}
    >{{ $value }}</textarea>
</x-form.reactive-label>
