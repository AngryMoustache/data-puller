<x-container class="p-8 flex flex-col gap-12 relative">
    <x-headers.h2 text="Settings" />

    <div class="w-full flex flex-col md:flex-row gap-8">
        <div class="w-full">
            <x-alpine.collapsible class="w-full" title="Origins" open>
                <livewire:settings.origin-settings />
            </x-alpine.collapsible>
        </div>

        <div class="w-full">
            <x-alpine.collapsible class="w-full" title="Artists" open>
                <livewire:settings.artist-settings />
            </x-alpine.collapsible>
        </div>
    </div>
</x-container>
