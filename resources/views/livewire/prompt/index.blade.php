<x-container class="flex flex-col md:flex-row gap-8">
    <div class="w-full md:w-1/2 flex flex-col gap-8">
        <x-headers.h2 text="Today's prompt" />

        <div class="flex flex-wrap gap-4">
            @foreach ($prompt->groupedTags as $group => $tags)
                <div class="flex flex-wrap gap-4">
                    @foreach ($tags as $tag)
                        <x-tag
                            :text="$tag->long_name"
                            class="text-base px-4 py-2"
                            href="{{ $tag->route() }}"
                            target="_blank"
                        />
                    @endforeach
                </div>
            @endforeach
        </div>

        <div class="flex flex-col md:flex-row gap-2">
            <x-form.input type="file" wire:model="sketch" />

            <x-form.button class="w-1/2 flex items-center justify-center" wire:click="uploadSketch">
                Upload finished sketch
            </x-form.button>
        </div>

        <x-headers.h2 text="Similar pulls for today's prompt" />

        <x-list>
            @foreach ($prompt->relatedPulls() as $pull)
                <x-lists.pull :$pull />
            @endforeach
        </x-list>
    </div>

    <div class="w-full md:w-1/2">
    </div>
</x-container>
