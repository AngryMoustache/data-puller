@props([
    'label' => null,
    'options' => collect(),
    'nullable' => false,
    'value' => null,
])

<select
    {{ $attributes->except('options')->merge([
        'class' => 'bg-surface px-3 py-3 text-lg rounded-lg w-full outline-none',
    ]) }}
>
    @if ($nullable)
        <option value=""></option>
    @endif

    @foreach ($options as $key => $label)
        <option
            value="{{ $key }}"
            @if($key === $value) selected="selected" @endif
        >
            {{ $label}}
        </option>
    @endforeach
</select>
