<x-alpine.infinite-scroll>
    <x-list>
        @foreach ($pulls as $pull)
            <x-lists.pull wire:key="{{ $pull->id }}" :$pull />
        @endforeach

        <div wire:loading wire:target="loadMore">
            @include('livewire.loading.list', [
                'size' => $perPage,
            ])
        </div>
    </x-list>
</x-alpine.infinite-scroll>
