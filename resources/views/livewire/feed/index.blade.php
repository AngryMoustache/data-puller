<x-container class="flex flex-col">
    <x-container class="flex gap-16 flex-col md:flex-row">
        <div class="flex flex-col gap-4 w-full md:w-1/2">
            {{-- <x-headers.h2 text="Manual pull" />

            <form wire:submit.prevent="pullTweet" class="flex gap-4 w-full mb-4">
                <x-form.input
                    wire:model="tweetUrl"
                    placeholder="Link to a tweet"
                />

                <x-form.button
                    text="Pull tweet"
                    class="rounded-lg whitespace-nowrap"
                    wire:click="pullTweet"
                />
            </form>

            <form wire:submit.prevent="pullScrape" class="flex gap-4 w-full mb-4">
                <x-form.input
                    wire:model="scrape.url"
                    placeholder="Link to a e-hentai first image"
                />

                <x-form.input
                    type="number"
                    wire:model="scrape.limit"
                    placeholder="Amount of images to pull"
                />

                <x-form.button
                    text="Scrape URL"
                    class="rounded-lg whitespace-nowrap"
                    wire:click="pullScrape"
                />
            </form> --}}

            <x-headers.h2 text="Newest pulls" />

            @if ($pulls->isNotEmpty())
                <x-list>
                    @foreach ($pulls as $pull)
                        <x-lists.feed :$pull />
                    @endforeach
                </x-list>
            @else
                <p class="opacity-50">No new pulls to handle</p>
            @endif
        </div>

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
</x-container>
