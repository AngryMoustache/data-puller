<x-container class="flex gap-16">
    <x-alpine.infinite-scroll class="flex flex-col gap-8 w-1/2" :enabled="$hasMorePulls">
        <x-headers.h2 text="Newest pulls" />
        <x-list>
            @foreach ($pulls as $pull)
                <x-lists.feed :$pull />
            @endforeach
        </x-list>

        @if ($hasMorePulls)
            <div wire:loading wire:target="loadMore">
                @include('livewire.loading.list', ['size' => $perPage])
            </div>
        @endif
    </x-alpine.infinite-scroll>

    <x-alpine.infinite-scroll class="flex flex-col gap-8 w-1/2" :enabled="$hasMoreArchived">
        <x-headers.h2 text="Archived pulls" />
        <x-list>
            @foreach ($archived as $pull)
                <x-lists.feed :$pull />
            @endforeach
        </x-list>

        @if ($hasMoreArchived)
            <div wire:loading wire:target="loadMore">
                @include('livewire.loading.list', ['size' => $perPage])
            </div>
        @endif
    </x-alpine.infinite-scroll>
</x-container>
