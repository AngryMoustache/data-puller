<x-container class="flex gap-16 flex-col md:flex-row">
    @if ($pulls->isNotEmpty())
        <div class="flex flex-col gap-8 w-full md:w-1/2">
            <x-headers.h2 text="Newest pulls" />

            <x-list>
                @foreach ($pulls as $pull)
                    <x-lists.feed :$pull />
                @endforeach
            </x-list>
        </div>
    @endif

    <x-alpine.infinite-scroll class="flex flex-col gap-8 w-full md:w-1/2 mx-auto" :enabled="$hasMore">
        <x-headers.h2 text="Archived pulls" />
        <x-list>
            @foreach ($archived as $pull)
                <x-lists.feed :$pull />
            @endforeach
        </x-list>

        @if ($hasMore)
            <div wire:loading wire:target="loadMore">
                @include('livewire.loading.list', ['size' => $perPage])
            </div>
        @endif
    </x-alpine.infinite-scroll>
</x-container>
