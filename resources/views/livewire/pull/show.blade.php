<div class="flex flex-col md:flex-row gap-8 p-4 md:p-8">
    <div class="w-full md:w-2/3 flex flex-col gap-4">
        @foreach ($pull->videos as $video)
            <x-video :src="$video->path()" class="w-full rounded" />
        @endforeach

        @foreach ($pull->attachments as $image)
            <x-img
                :src="$image->path()"
                class="rounded"
                :width="$image->width"
                :height="$image->height"
            />
        @endforeach
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

            <p>
                <span class="opacity-50">Pulled</span>
                <span class="mx-1">{{ $pull->verdict_at->diffForHumans() }}</span>
                <span class="opacity-50">from</span>

                <x-origin
                    class="mx-2"
                    :origin="$pull->origin"
                    :href="$pull->source_url"
                    :label="$pull->artist?->name"
                />
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            @foreach ($pull->tags->where('hidden', 0) as $tag)
                <x-tag
                    :text="$tag->long_name"
                    href="{{ $tag->route() }}"
                />
            @endforeach
        </div>

        <div class="flex flex-col gap-4">
            <x-headers.h2 text="Folders" />

            @foreach ($folders as $folder)
                <x-folder :$folder wire:key="folder-{{ $folder->id }}">
                    <x-form.button-secondary
                        wire:click="toggleFromFolder({{ $folder->id }})"
                        class="m-2 flex items-center"
                    >
                        @if ($pull->folders->pluck('id')->contains($folder->id))
                            <x-heroicon-o-minus class="w-5 h-5" />
                        @else
                            <x-heroicon-o-plus class="w-5 h-5" />
                        @endif
                    </x-form.button-secondary>
                </x-folder>
            @endforeach

            <x-form.button-secondary
                text="Create new folder"
                class="w-fit"
                x-on:click="window.openModal('new-folder', {
                    pullId: {{ $pull->id }},
                })"
            />
        </div>

        <div class="flex flex-col gap-4">
            <x-headers.h2 text="Related pulls" />
            <livewire:sections.related :pull="$pull" />
        </div>
    </div>
</div>
