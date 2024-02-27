<x-container class="flex flex-col">
    <x-container class="flex gap-16 flex-col md:flex-row">
        <div class="flex flex-col gap-8 w-full md:w-1/2">
            <x-headers.h2 text="Newest pulls" />

            @if ($pulls->isNotEmpty())
                <x-alpine.infinite-scroll :enabled="$hasMore">
                    <x-list>
                        @foreach ($pulls as $pull)
                            <x-lists.feed :$pull />
                        @endforeach
                    </x-list>
                </x-alpine.infinite-scroll>

                @if ($hasMore)
                    <div wire:loading wire:target="loadMore">
                        @include('livewire.loading.list', ['size' => $perPage])
                    </div>
                @endif
            @else
                <p class="opacity-50">No new pulls to handle</p>
            @endif
        </div>

        <div class="flex flex-col gap-12 w-full md:w-1/2">
            <div class="flex flex-col gap-4">
                <x-headers.h2 text="Request sync" />

                <div class="flex gap-2 flex-wrap mb-4">
                    <x-form.button wire:click="syncOrigin">
                        Sync all origins
                    </x-form.button>

                    @foreach ($origins as $origin)
                        @if ($origin->type->canPull())
                            <x-form.button-secondary wire:click="syncOrigin({{ $origin->id }})">
                                {{ $origin->name }}
                                <i class="ml-2 {{ $origin->type->icon() }}"></i>
                            </x-form.button-secondary>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <x-headers.h2 text="Manual pull" />

                <div class="flex flex-col gap-2">
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
                    </form>

                    <form wire:submit.prevent="pullKemeno" class="flex gap-4 w-full mb-4">
                        <x-form.input
                            wire:model="kemeno.url"
                            placeholder="Link to a kemeno post"
                        />

                        <x-form.button
                            text="Pull URL"
                            class="rounded-lg whitespace-nowrap"
                            wire:click="pullKemeno"
                        />
                    </form>

                    <div class="flex">
                        <x-form.button-secondary :href="route('feed.create')">
                            <div class="flex items-center gap-2">
                                Create from scratch
                                <x-heroicon-o-document-plus class="w-5 h-5" />
                            </div>
                        </x-form.button-secondary>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <x-headers.h2 text="Archive" />

                <p>You have {{ $archiveCount }} pulls in the archive</p>

                <div class="flex">
                    <x-form.button-secondary
                        text="View archive"
                        :href="route('archive.index')"
                    />
                </div>
            </div>
        </div>
    </x-container>
</x-container>
