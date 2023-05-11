<x-container class="flex flex-col md:flex-row gap-12">
    <div class="w-full md:w-1/2 flex flex-col gap-12">
        <div class="w-full flex flex-col gap-8">
            <x-headers.h2 :text="$prompt->name" />

            <p class="text-lg">
                {{ $prompt->description }}
            </p>
        </div>

        @if ($prompt->pull)
            <div class="w-full flex flex-col gap-8">
                <x-headers.h2 text="Uploaded sketch" />

                <x-list>
                    <x-lists.pull :pull="$prompt->pull" />
                </x-list>
            </div>
        @endif

        <div class="w-full flex flex-col gap-8">
            <x-headers.h2 text="Tags" />
            <div class="flex flex-col gap-4">
                @foreach ($prompt->groupedTags as $group => $tags)
                    <div class="flex flex-wrap gap-4">
                        <x-headers.h3 :text="$group" />

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
        </div>

        @if (! $prompt->pull)
            <div class="flex flex-col gap-4">
                <x-headers.h2 text="Upload finished sketch" />

                <x-form.input type="file" wire:model="sketch" wire:loading.remove />

                <div wire:loading>
                    <p>Uploading sketch, hold on...</p>
                </div>
            </div>
        @endif
    </div>

    <div class="w-full md:w-1/2 flex flex-col gap-8">
        <x-headers.h2 text="Similar pulls for today's prompt" />

        <x-list>
            @foreach ($prompt->relatedPulls(5) as $pull)
                <x-lists.pull :$pull />
            @endforeach
        </x-list>
    </div>
</x-container>
