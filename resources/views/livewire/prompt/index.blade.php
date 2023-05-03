<x-container class="flex flex-col md:flex-row gap-12">
    <div class="w-full md:w-1/2 flex flex-col gap-8">
        <x-headers.h2 text="Today's prompt" />

        <div class="flex flex-col gap-4">
            @foreach ($prompt->groupedTags as $group => $tags)
                <x-headers.h3 :text="$group" />
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

        <div class="flex flex-col md:flex-row gap-2" wire:loading.remove>
            <x-form.input type="file" wire:model="sketch" />
        </div>

        <div wire:loading>
            <x-headers.h3 text="Uploading sketch..." />
        </div>

        <x-headers.h2 text="Similar pulls for today's prompt" />

        <x-list>
            @foreach ($prompt->relatedPulls() as $pull)
                <x-lists.pull :$pull />
            @endforeach
        </x-list>
    </div>

    <div class="w-full md:w-1/2 flex flex-col gap-8">
        <x-headers.h2 text="Previous prompts" />

        <x-list>
            @foreach ($previous as $prompt)
                <x-lists.pull :pull="$prompt->pull" />
            @endforeach
        </x-list>
    </div>
</x-container>
