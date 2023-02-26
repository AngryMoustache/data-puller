<x-container class="flex flex-col gap-8">
    <div class="w-full flex flex-col gap-4">
        @foreach ($pull->videos as $video)
            <x-video src="{{ $video->path() }}" />
        @endforeach

        @foreach ($pull->attachments as $image)
            <x-image class="w-full rounded-lg overflow-hidden" src="{{ $image->path() }}" />
        @endforeach
    </div>

    <x-surface class="p-0">
        <x-pull.info name-length="100" :$pull />

        @if ($pull->tags->isNotEmpty())
            <div class="w-auto p-4 pt-0 flex overflow-x-auto gap-4">
                @foreach ($pull->tagList() as $tag)
                    <x-tag :$tag />
                @endforeach
            </div>
        @endif
    </x-surface>

    <x-grid.pulls :pulls="$pull->related" />
</x-container>
