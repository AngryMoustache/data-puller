@props([
    'label' => null,
    'options' => collect(),
    'nullable' => false,
])

<x-form.reactive-label :$label>
    <select
        data-label-target
        x-on:change="setLabelStatus()"
        {{ $attributes->except('options')->merge([
            'class' => 'bg-background px-3 py-3 text-lg rounded-lg w-full outline-none',
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
