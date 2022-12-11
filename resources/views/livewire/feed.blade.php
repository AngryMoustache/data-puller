<x-container>
    <x-loading-section class="flex gap-4 mt-4" wire:target="save">
        <div class="w-1/3 flex gap-4 flex-col">
            @foreach ($pull->videos as $video)
                <x-video src="{{ $video->path() }}" />
            @endforeach

            @foreach ($pull->attachments as $image)
                <x-image class="w-full rounded-lg overflow-hidden" src="{{ $image->path() }}" />
            @endforeach
        </div>

        <div
            class="w-2/3 flex flex-col gap-8"
            x-data="{
                name: @entangle('fields.name').defer,
                artist: @entangle('fields.artist').defer,
                folders: @entangle('fields.folders').defer,
            }"
        >
            <x-form.input :value="$fields['name']" x-model="name" label="Name" />
            <x-form.input :value="$fields['artist']" x-model="artist" label="Artist name" />

            <x-surface>
                <div class="flex w-full flex-col gap-2">
                    @foreach ($folders as $folder)
                        <x-form.checkbox
                            wire:model.defer="fields.folders.{{ $folder->id }}"
                            :label="$folder->name"
                            class="text-lg"
                        />
                    @endforeach
                </div>

                <div class="mt-8">
                    <x-form.button-icon
                        class="text-sm"
                        x-on:click="$wire.emit('openModal', 'new-folder')"
                        text="New folder"
                        icon="fas fa-plus"
                    />
                </div>
            </x-surface>

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
    </x-loading-section>
</x-container>
