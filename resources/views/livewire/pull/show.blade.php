<div class="flex flex-col md:flex-row gap-8 p-4 md:p-8">
    <div class="w-full md:w-2/3 flex flex-col gap-4">
        @foreach ($pull->videos as $video)
            <x-video :src="$video->path()" class="w-full rounded" />
        @endforeach

        @foreach ($pull->attachments as $image)
            <x-img :src="$image->path()" class="w-full rounded" />
        @endforeach
    </div>

    <div class="w-full md:w-1/3 flex flex-col gap-8">
        <div class="flex flex-col gap-2">
            <x-headers.h1 :text="$pull->name" />

            @if ($pull->artist)
                <p>
                    <span class="opacity-50">Artist</span>
                    <a href="{{ $pull->artist->route() }}" class="mx-1">
                        {{ $pull->artist->name }}
                    </a>
                </p>
            @endif

            <p>
                <span class="opacity-50">Pulled</span>
                <span class="mx-1">{{ $pull->verdict_at->diffForHumans() }}</span>
                <span class="opacity-50">by</span>
                <x-origin class="mx-2" :origin="$pull->origin" href="{{ $pull->source_url }}" />
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

        {{-- <div class="flex flex-col gap-4">
            <x-headers.h2 text="Folders" />

            <x-form.button
                text="Save to folder"
                class="w-full"
                wire:click="$set('showAddToFolderModal', true)"
            />
        </div> --}}

        <div class="flex flex-col gap-4">
            <x-headers.h2 text="Related pulls" />
            <livewire:sections.related :pull="$pull" />
        </div>
    </div>
</div>
