<x-container class="flex gap-4">
    <div class="w-1/3">
        <x-image
            class="rounded-lg w-full"
            :src="$pull->image->path()"
        />
    </div>

    <div
        class="w-2/3 flex flex-col gap-8"
        x-data="{
            name: @entangle('fields.name').defer,
            artist: @entangle('fields.artist').defer,
            tags: @entangle('fields.tags').defer,
        }"
    >
        <x-form.input :value="$fields['name']" x-model="name" label="Name" />
        <x-form.input :value="$fields['artist']" x-model="artist" label="Artist name" />
        <x-form.textarea
            x-model="tags"
            :value="$fields['tags']"
            label="Tags (comma seperated)"
            class="h-64"
        />

        <div class="flex gap-4 justify-end">
            <x-form.button-secondary class="flex justify-center items-center gap-3" wire:click="save('offline')">
                Skip pull
                <i class="fas fa-times"></i>
            </x-form.button-secondary>

            <x-form.button class="flex justify-center items-center gap-3" wire:click="save('online')">
                Save pull
                <i class="fas fa-search"></i>
            </x-form.button>
        </div>
    </div>
</x-container>
