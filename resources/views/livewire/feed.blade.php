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
                tags: @entangle('fields.tags').defer,
            }"
        >
            <x-form.input :value="$fields['name']" x-model="name" label="Name" />
            <x-form.input :value="$fields['artist']" x-model="artist" label="Artist name" />

            <div
                x-data="{ open: {{ $tagGroups->keys()->first() }} }"
                class="flex flex-col gap-4"
            >
                <div class="flex justify-between items-end">
                    <ul class="flex gap-2">
                        @foreach ($tagGroups as $key => $group)
                            <li
                                x-on:click="open = {{ $key }}"
                                :class="{ 'bg-gradient-dark': open === @js($key) }"
                                class="
                                    inline-block text-no-wrap px-4 py-2 rounded-xl text-sm
                                    cursor-pointer transition-all hover:scale-105
                                "
                            >
                                {{ $group->name }}
                            </li>
                        @endforeach
                    </ul>

                    <x-form.button-icon
                        class="text-sm"
                        x-on:click="$wire.emit('openModal', 'new-tag-group')"
                        text="New tag group"
                        icon="fas fa-plus"
                    />
                </div>

                @foreach ($tagGroups as $key => $group)
                    <template x-if="open === {{ $key }}">
                        <x-surface class="flex flex-col gap-4">
                            @foreach ($group->tags as $tag)
                                <x-form.checkbox
                                    wire:model.defer="fields.tags.{{ $tag->id }}"
                                    :label="$tag->name"
                                    class="text-lg"
                                />
                            @endforeach

                            <div class="mt-4">
                                <x-form.button-icon
                                    class="text-sm"
                                    x-on:click="$wire.emit('openModal', 'new-tag', {{ $group->id }})"
                                    text="Add tag"
                                    icon="fas fa-plus"
                                />
                            </div>
                        </x-surface>
                    </template>
                @endforeach
            </div>

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
