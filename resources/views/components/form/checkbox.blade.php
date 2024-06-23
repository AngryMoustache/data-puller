@props([
    'id' => Str::random(16),
    'label' => $slot,
])

<div>
    <label for="{{ $id }}" {{ $attributes->except(['x-on', 'x-bind:checked', 'label', 'tag', 'value', 'x-model', 'checked'])->merge([
        'class' => 'p-2 flex w-full items-center gap-4 cursor-pointer',
    ]) }}>
        <div class="relative">
            <input
                id="{{ $id }}"
                type="checkbox"
                class="hidden peer"
                {{ $attributes->whereStartsWith(['x-on', 'x-bind:checked', 'x-model', 'wire:model', 'value', 'checked']) }}
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

        @if ((string) $label !== '')
            <span>{{ $label }}</span>
        @endif
    </label>
</div>
