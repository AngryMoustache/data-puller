<x-card>
    <div wire:loading.remove {{ $attributes->merge(['class' => '']) }}>
        {{ $slot }}
    </div>

    <div wire:loading.flex {{ $attributes->only('wire:target') }} class="justify-center w-full py-6">
        <x-loading />
    </div>
</x-card>
