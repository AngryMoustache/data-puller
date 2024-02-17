<div class="flex gap-4 w-full">
    <div class="w-64 overflow-hidden rounded" style="aspect-ratio: 3/2.5">
        <x-img
            src="{{ $pull->attachment?->format('thumb') }}"
            :width="3"
            :height="2.5"
        />
    </div>

    <div class="w-full flex flex-col justify-between py-2">
        <div class="w-full flex flex-col gap-2">
            <div class="flex items-center justify-between">
                <p class="line-clamp-1">
                    <span class="text-dark mr-2">
                        #{{ $pull->id }}
                    </span>

                    <span class="font-bold">
                        {{ $pull->name }}
                    </span>
                </p>
            </div>

            <p>
                <span class="opacity-50">Pulled</span>
                <span class="mx-1">{{ $pull->created_at->diffForHumans() }}</span>
                <span class="opacity-50">by</span>
                <x-origin
                    class="mx-2"
                    :href="$pull->artist?->route()"
                    :origin="$pull->origin"
                    :label="$pull->artist?->name"
                    :source-url="$pull->source_url"
                />
            </p>
        </div>

        <div class="flex">
            <x-form.button-secondary
                href="{{ route('feed.show', $pull) }}"
                text="Review & pull"
            />
        </div>
    </div>
</div>
