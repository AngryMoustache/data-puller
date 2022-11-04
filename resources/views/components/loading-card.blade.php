<x-card>
    <div wire:loading.remove {{ $attributes->merge(['class' => '']) }}>
        {{ $slot }}
    </div>

    <div wire:loading.flex {{ $attributes->only('wire:target') }}>
        <x-loading />
    </div>
</x-card>
