@props([
    'id' => Str::random(16),
    'label' => 'Label',
])

<div>

    <label for="{{ $id }}" {{ $attributes->except(['label', 'tag'])->merge([
        'class' => 'p-2 flex w-full items-center gap-4',
    ]) }}>
        <div class="relative">
            <input
                id="{{ $id }}"
                type="checkbox"
                class="hidden peer"
                {{ $attributes->whereStartsWith(['x-model', 'wire:model']) }}
            />

            <div class="
                border border-border rounded-lg p-3
                peer-checked:border-primary
            "></div>

            <x-heroicon-o-check style="top: 3px; left: 3px" class="
                w-5 h-5 text-primary absolute inset-0
                hidden peer-checked:block
            " />
        </div>

        <span>{{ $label }}</span>
    </label>
</div>
