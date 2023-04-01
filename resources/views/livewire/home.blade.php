<x-container class="flex flex-col gap-12 py-12">
    <div class="flex flex-col gap-8">
        <x-headers.h1 text="Based on recent viewings" />
        <livewire:sections.recommendations />
    </div>

    <div class="flex flex-col gap-8">
        <x-headers.h1 text="Newest pulls" />
        <livewire:sections.newest />
    </div>
</x-container>
