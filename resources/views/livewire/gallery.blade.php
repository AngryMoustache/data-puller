<x-container>
    <ul class="flex gap-4 text-3xl">
        <x-filter.display :current="$bag->display" :value="Display::CARD" icon="fas fa-th-large" />
        <x-filter.display :current="$bag->display" :value="Display::COMPACT" icon="fas fa-th" />
        <x-filter.display :current="$bag->display" :value="Display::LIST" icon="fas fa-list" />
    </ul>

    <div wire:loading class="w-full">
        <x-loading />
    </div>

    <div wire:loading.remove>
        <x-grid.pulls
            :display="$bag->display"
            :pulls="$bag->pulls()"
        />
    </div>
</x-container>
