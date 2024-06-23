@props([
    'label' => null,
    'options' => collect(),
    'selected' => null,
])

<div {{ $attributes->except('options')->merge(['class' => '
    flex flex-col gap-2 p-4 w-full
']) }}>
    @if ($label)
        <x-headers.h2 class="pb-4">{{ $label }}</x-headers.h2>
    @endif

    @foreach ($options as $key => $value)
        <label class="inline-flex items-center">
            <input
                type="radio"
                value="{{ $key }}"
                {{ $attributes->whereStartsWith(['wire:model', 'x-model']) }}
                class="peer hidden"
            >

            <x-heroicon-o-chevron-right class="
                w-8 h-8 text-primary pr-2
                peer-checked:opacity-100 opacity-0
            " />

            <span class="
                opacity-50 cursor-pointer
                peer-checked:opacity-100 peer-checked:font-semibold
            ">
                {!! $value !!}
            </span>
        </label>
    @endforeach
</div>
