<x-container>
    <x-loading-section>
        <x-grid.pulls :$display :pulls="$folder->pulls->sortByDesc('verdict_at')" />
    </x-loading-section>

    {{-- <x-loading-section class="mt-16" wire:target="addPage">
        <x-triggers.infinite-scroll :stopped="$maxPulls <= $pulls->count()" />
    </x-loading-section> --}}
</x-container>
