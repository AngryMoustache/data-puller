<x-container class="flex flex-col gap-12 py-12">
    <x-alpine.infinite-scroll>
        <x-grid>
            @foreach ($pulls as $pull)
                <x-cards.pull :$pull />
            @endforeach
        </x-grid>
    </x-alpine.infinite-scroll>

    <div wire:loading wire:target="loadMore">
        @include('livewire.loading.grid', [
            'size' => 5,
        ])
    </div>
</x-container>
