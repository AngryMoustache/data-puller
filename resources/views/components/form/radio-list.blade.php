@props([
    'label' => null,
    'options' => collect(),
    'nullable' => false,
    'selected' => null,
])

<div {{ $attributes->except('options')->merge(['class' => '
    flex flex-col gap-2 p-4 w-full
']) }}>
    @if ($label)
        <x-headers.h2>{{ $label }}</x-headers.h2>
    @endif

    @if ($nullable)
        <label class="inline-flex items-center">
            <input
                type="radio"
                value=""
                {{ $attributes->whereStartsWith(['wire:model', 'x-model']) }}
                class="peer hidden"
            >

            <span class="
                opacity-50 cursor-pointer
                peer-checked:opacity-100 peer-checked:font-semibold
            ">
                Show all
            </span>
        </label>
    @endif

    @foreach ($options as $key => $value)
        <label class="inline-flex items-center">
            <input
                type="radio"
                value="{{ $key }}"
                {{ $attributes->whereStartsWith(['wire:model', 'x-model']) }}
                class="peer hidden"
            >

            <span class="
                opacity-50 cursor-pointer
                peer-checked:opacity-100 peer-checked:font-semibold
            ">
                {!! $value !!}
            </span>
        </label>
    @endforeach
</div>
