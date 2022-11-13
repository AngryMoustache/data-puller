@props([
    'label' => null,
    'value' => null,
    'options' => collect(),
    'nullable' => false,
])

<x-form.reactive-label :$value :$label>
    <select
        x-model="value"
        x-on:change="activated = !! value"
        {{ $attributes->only('class')->merge([
            'class' => 'bg-background px-4 py-4 text-lg rounded-lg w-full outline-none',
        ]) }}
    >
        @if ($nullable)
            <option value=""></option>
        @endif

        @foreach ($options as $key => $value)
            <option value="{{ $key }}">
                {{ $value}}
            </option>
        @endforeach
    </select>
</x-form.reactive-label>
