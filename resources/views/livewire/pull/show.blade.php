<div class="flex flex-col md:flex-row gap-8 p-4 md:p-8">
    <div class="w-full md:w-2/3 flex flex-col gap-4">
        <livewire:feed.media-list :media="$pull->media->map->toJson(false)->toArray()" />
    </div>

    <div class="w-full md:w-1/3 flex flex-col gap-8">
        <div class="flex flex-col gap-2">
            <x-headers.h1 class="flex items-center justify-between">
                <div class="pr-2 flex flex-col">
                    @if ($pull->original_name)
                        <span class="opacity-50 text-sm">
                            {{ $pull->original_name }}
                        </span>
                    @endif
                    <span>{{ $pull->name }}</span>
                </div>

                <div class="flex gap-2">
                    <x-form.button-secondary
                        text="Translate"
                        href="{{ route('pull.translate', $pull) }}"
                        class="text-sm"
                    />

                    <x-form.button-secondary
                        text="Edit"
                        href="{{ route('feed.show', $pull) }}"
                        class="text-sm"
                    />
                </div>
            </x-headers.h1>

            <p class="flex flex-wrap items-center">
                <span class="opacity-50">Pulled</span>
                <span class="mx-1">{{ $pull->verdict_at->diffForHumans() }}</span>
                <span class="opacity-50">from</span>

                <x-origin
                    class="mx-2"
                    :origin="$pull->origin"
                    :href="$pull->artist?->route()"
                    :label="$pull->artist?->name ?? 'Unknown'"
                    :source-url="$pull->canHaveSourceUrl() ? $pull->source_url : null"
                />
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            @foreach ($pull->tagGroups as $group)
                @foreach ($group->tags as $tag)
                    <x-tag
                        :text="$tag->long_name"
                        :icon="$tag->icon"
                        href="{{ $tag->route() }}"
                    />
                @endforeach
            @endforeach
        </div>

        <x-alpine.collapsible title="Rating">
            <div class="w-full p-4">
                <livewire:sections.rating :pull="$pull" />
            </div>
        </x-alpine.collapsible>

        <livewire:sections.folder-list :pull="$pull" />

        <x-alpine.collapsible title="Story">
            <div
                class="flex flex-col gap-4"
                x-data="{
                    current: 0,
                    body: null,
                    async init () {
                        this.body = await this.$wire.getStory(0)
                        this.$watch('current', (value) => {
                            this.update(value)
                        })

                        this.$wire.on('load-new-story', () => {
                            this.current = 0
                            this.update()
                        })
                    },
                    async update (key = 0) {
                        this.body = await this.$wire.getStory(key)
                    }
                }"
            >
                <div class="flex gap-4 items-center">
                    @if ($stories->isNotEmpty())
                        <x-form.select
                            :options="$stories->pluck('title')"
                            x-model="current"
                        />
                    @endif

                    <div
                        wire:loading.remove
                        wire:target="generateStory"
                    >
                        <x-form.button-secondary
                            text="Generate"
                            wire:click="generateStory"
                            class="w-fit"
                        />
                    </div>

                    <div wire:loading wire:target="generateStory">
                        <x-form.button-secondary
                            text="Generating..."
                        />
                    </div>
                </div>

                <div x-html="body" class="p-2" x-show="body !== null"></div>
            </div>
        </x-alpine.collapsible>

        <div class="flex flex-col gap-4">
            <x-headers.h2 text="Related pulls" />
            <livewire:sections.related :pull="$pull" />
        </div>
    </div>
</div>
