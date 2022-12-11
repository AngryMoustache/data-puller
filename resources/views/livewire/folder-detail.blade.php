<x-container>
    <x-loading-section>
        <x-grid.pulls :$display :pulls="$folder->pulls->sortByDesc('verdict_at')" />
    </x-loading-section>
</x-container>
