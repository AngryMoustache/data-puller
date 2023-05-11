<x-container class="flex flex-col gap-12 py-12">
    <x-section label="Newest pulls">
        <livewire:sections.newest />
    </x-section>

    <x-section label="Recommendations">
        <livewire:sections.recommendations />
    </x-section>

    <x-section label="Hidden gems">
        <livewire:sections.hidden-gems />
    </x-section>

    <x-section label="Based on recent viewings">
        <livewire:sections.history-recommendations />
    </x-section>
</x-container>
