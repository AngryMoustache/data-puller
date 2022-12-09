<x-container>
    <x-loading-section class="flex gap-4 mt-4" wire:target="save">
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

            <div class="flex justify-end">
                <x-form.button-icon
                    x-on:click="$wire.emit('openModal', 'new-tag-group')"
                    text="New tag group"
                    icon="fas fa-plus"
                />
            </div>

            @foreach ($tagGroups as $group)
                <x-surface class="flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <x-headers.h3 text="{{ $group->name }}" />

                        <div class="flex gap-2">
                            {{-- <x-form.button-icon
                                class="px-3 text-sm"
                                x-on:click="$wire.emit('openModal', 'new-tag', {{ $group->id }})"
                                icon="fa fa-pencil"
                            /> --}}

                            <x-form.button-icon
                                class="px-3 text-sm"
                                x-on:click="$wire.emit('openModal', 'new-tag', {{ $group->id }})"
                                icon="fas fa-plus"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-6 gap-4">
                        @forelse ($group->tags as $tag)
                            <x-form.checkbox
                                wire:model.defer="fields.tags.{{ $tag->id }}"
                                :label="$tag->name"
                                class="text-lg"
                            />
                        @empty
                            <x-form.button-icon
                                class="px-3 text-sm"
                                x-on:click="$wire.emit('openModal', 'new-tag', {{ $group->id }})"
                                text="Add tag"
                                icon="fas fa-plus"
                            />
                        @endforelse
                    </div>
                </x-surface>
            @endforeach

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
