<div class="flex flex-col md:flex-row gap-8 p-4 md:p-8">
    <div class="w-full md:w-2/3 flex flex-col gap-4">
        <livewire:feed.media-list :media="$pull->media->map->toJson(false)->toArray()" />
    </div>

    <div class="w-full md:w-1/3 flex flex-col gap-8">
        <div class="flex flex-col gap-2">
            <x-headers.h1 class="flex items-center justify-between">
                <span>{{ $pull->name }}</span>

                <x-form.button-secondary
                    text="Edit"
                    href="{{ route('feed.show', $pull) }}"
                    class="text-sm"
                />
            </x-headers.h1>

            <p class="flex flex-wrap items-center">
                <span class="opacity-50">Pulled</span>
                <span class="mx-1">{{ $pull->verdict_at->diffForHumans() }}</span>
                <span class="opacity-50">from</span>

                <x-origin
                    class="mx-2"
                    :origin="$pull->origin"
                    :href="$pull->artist?->route()"
                    :label="$pull->artist?->name"
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

        <livewire:sections.folder-list :pull="$pull" />

        <div class="flex flex-col gap-4">
            <x-headers.h2 text="Related pulls" />
            <livewire:sections.related :pull="$pull" />
        </div>
    </div>
</div>
