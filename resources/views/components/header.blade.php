<div {{ $attributes->merge(['class' => 'w-full bg-gradient mb-8']) }}>
    <x-container class="py-12">
        <div class="mx-4">
            {{ $slot }}
        </div>
    </x-container>

    {{-- <x-container>
        <x-navigation class="mx-4" />
    </x-container> --}}
</div>
