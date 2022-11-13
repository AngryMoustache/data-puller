@props([
    'value' => '',
    'label',
])

<x-form.reactive-label :$value :$label>
    <input
        x-model="value"
        x-on:focus="activated = true"
        x-on:blur="activated = !! value"
        {{ $attributes->merge([
            'class' => 'bg-background px-4 py-2 text-lg rounded-lg w-full outline-none',
        ]) }}
    />
</x-form.reactive-label>
