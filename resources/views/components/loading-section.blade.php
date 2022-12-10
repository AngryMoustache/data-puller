<div
    wire:loading
    {{ $attributes->only(['wire:target'])->merge([
        'class' => 'w-full',
    ]) }}
>
    <x-loading />
</div>

<div
    wire:loading.remove
    {{ $attributes->only(['wire:target', 'class'])->merge([
        'class' => 'w-full',
    ]) }}
>
    {{ $slot }}
</div>
