<x-container class="w-full flex gap-8">
    <div class="w-1/3 flex flex-col gap-4">
        @foreach ($pull->videos as $video)
            <x-video :src="$video->path()" class="w-full rounded" />
        @endforeach

        @foreach ($pull->attachments as $image)
            <x-img :src="$image->path()" class="w-full rounded" />
        @endforeach
    </div>

    <div class="w-2/3 flex flex-col gap-16 py-4">
        <div class="w-full flex flex-col gap-4">
            <x-headers.h1 :text="$pull->name" />

            <p>
                <span class="opacity-50">Pulled</span>
                <span class="mx-1">{{ $pull->created_at->diffForHumans() }}</span>
                <span class="opacity-50">by</span>
                <x-origin class="mx-2" :origin="$pull->origin" />
            </p>
        </div>

        <div class="flex flex-col gap-4">
            <x-headers.h2 text="General information" />

            <div class="p-2 flex flex-col gap-4">
                <x-form.input
                    label="Name"
                    placeholder="Name of the pull"
                    wire:model.defer="fields.name"
                />

                <x-form.input
                    label="Artist name"
                    placeholder="Name of the artist"
                    :value="$pull->name"
                    wire:model.defer="fields.artist"
                />
            </div>
        </div>

        <div class="flex flex-col gap-4">
            @foreach ($tags as $group)
                <x-alpine.collapsible :open="$loop->first" :title="$group->name">
                    <div x-show="open" class="pt-2">
                        <x-form.tag-tree :tag="$group" />
                    </div>
                </x-alpine.collapsible>
            @endforeach
        </div>

        <div class="flex justify-end gap-4">
            <x-form.button-secondary
                wire:click="save('offline')"
                text="Archive"
            />

            <x-form.button-secondary
                wire:click="save('pending')"
                text="Save and continue"
            />

            <x-form.button
                wire:click="save('online')"
                text="Publish"
            />
        </div>
    </div>
</x-container>
