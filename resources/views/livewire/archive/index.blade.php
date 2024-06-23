<x-container class="flex flex-col">
    <x-container class="flex gap-16 flex-col md:flex-row">
        <x-alpine.infinite-scroll class="flex flex-col gap-8 w-full" :enabled="$hasMore">
            <x-headers.h2 text="Archived pulls" />

            <div class="flex w-full gap-4">
                <x-form.input
                    wire:model.live.debounce="query"
                    placeholder="Search archive..."
                    class="w-full py-3"
                />

                <div class="!w-1/4">
                    <x-form.select
                        class="rounded-xl"
                        wire:model.live="sort"
                        :options="[
                            'created_at--desc' => 'Newest',
                            'created_at--asc' => 'Oldest',
                        ]"
                    />
                </div>
            </div>

            <x-grid>
                @foreach ($pulls as $pull)
                    <x-cards.feed :$pull />
                @endforeach
            </x-grid>

            @if ($hasMore)
                <div wire:loading wire:target="loadMore">
                    @include('livewire.loading.grid', ['size' => $perPage])
                </div>
            @endif
        </x-alpine.infinite-scroll>
    </x-container>
</x-container>
