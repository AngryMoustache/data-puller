<x-container class="w-full flex flex-col relative md:flex-row gap-8">
    <div class="hidden-scroll w-full md:sticky md:top-2 md:w-1/3 md:min-h-feed">
        <livewire:feed.media-list :media="$media" />
    </div>

    <div class="w-full md:w-2/3 flex flex-col gap-8 py-4">
        <div class="w-full flex flex-col gap-4">
            <x-headers.h1 :text="$pull->name" />

            <p>
                <span class="opacity-50">Pulled</span>
                <span class="mx-1">{{ $pull->created_at->diffForHumans() }}</span>
                <span class="opacity-50">by</span>
                <x-origin class="mx-2"
                    href="{{ $pull->source_url }}"
                    :origin="$pull->origin"
                    :label="$pull->artist?->name"
                />
            </p>
        </div>

        <x-alpine.collapsible open title="General information">
            <div class="flex flex-col gap-4">
                <div class="flex gap-2">
                    <x-form.input
                        label="Name"
                        placeholder="Name of the pull"
                        wire:model="fields.name"
                    />

                    <x-form.button class="flex items-center" wire:click.prevent="generateName">
                        <x-heroicon-o-arrow-path class="w-5 h-5" />
                    </x-form.button>
                </div>

                <x-form.input
                    label="Artist name"
                    placeholder="Name of the artist"
                    :value="$pull->name"
                    wire:model="fields.artist"
                />

                <x-form.input
                    label="Source URL"
                    placeholder="Source URL"
                    :value="$pull->source_url"
                    wire:model="fields.source_url"
                />
            </div>
        </x-alpine.collapsible>

        <x-alpine.collapsible title="Media" open>
            <div
                class="flex flex-col gap-4"
                x-data="{
                    list: @entangle('media'),
                    sortable: null,
                    config: {
                        animation: 150,
                        ghostClass: 'opacity-20',
                        handle: '.sortable-handle',
                    },
                    init () {
                        this.sortable = Sortable.create(this.$refs.items, this.config)
                    },
                    removeMedia (key) {
                        this.list.splice(key, 1)
                        $wire.dispatch('update-media-list', [this.list])
                    },
                    addToThumbnails (key) {
                        $wire.dispatch('add-to-thumbnails', [this.list[key]])
                    },
                    reorder (e) {
                        const list = Alpine.raw(this.sortable.toArray().splice(1))
                            .map(id => this.list.find(media => 'media-' + media.id === id))

                        this.list = []

                        window.setTimeout(() => {
                            this.list = list

                            $wire.dispatch('update-media-list', [list])
                        }, 0)
                    },
                }"
            >
                <div
                    class="flex flex-col gap-4"
                    x-ref="items"
                    x-on:update="reorder"
                >
                    <template x-for="(media, key) in list" :key="media.id">
                        <div
                            :data-id="'media-' + media.id"
                            class="
                                flex items-center gap-4
                                p-2 border border-border rounded-lg bg-surface
                            "
                        >
                            <x-heroicon-o-bars-3
                                class="sortable-handle w-12 h-6 cursor-move"
                            />

                            <div class="w-16 h-16">
                                <img
                                    class="w-full bg-border rounded-lg aspect-square"
                                    :src="media.thumbnail"
                                />
                            </div>

                            <div class="grow">
                                <p class="line-clamp-1">
                                    <span x-text="media.name"></span>
                                </p>

                                <p class="opacity-50 text-sm">
                                    <span x-text="media.width"></span>px x
                                    <span x-text="media.height"></span>px
                                </p>
                            </div>

                            <x-heroicon-o-photo
                                class="w-12 h-6 cursor-pointer hover:text-primary"
                                x-on:click="addToThumbnails(key)"
                            />

                            <x-heroicon-o-trash
                                class="w-12 h-6 cursor-pointer hover:text-primary"
                                x-on:click="removeMedia(key)"
                            />
                        </div>
                    </template>
                </div>

                <div class="flex gap-4">
                    <x-form.button-secondary
                        text="Add more media"
                        x-on:click="window.openModal('add-attachment', {
                            selected: list.map(media => media.id),
                        })"
                    />
                </div>
            </div>
        </x-alpine.collapsible>

        <x-alpine.collapsible title="Thumbnails" open>
            <div
                class="flex flex-col gap-4"
                x-on:add-to-thumbnails.window="list.push($event.detail[0])"
                x-data="{
                    list: @entangle('thumbnails'),
                    tagList: @entangle('fields.tags'),
                    init () {
                        console.log(this.list)
                    },
                }"
            >
                <template x-for="(media, key) in list" :key="key + '-' + media.id">
                    <div
                        :data-id="'media-' + media.id"
                        class="
                            flex items-center gap-4
                            p-2 border border-border rounded-lg bg-surface
                        "
                    >
                        <div class="w-16 h-16">
                            <img
                                class="w-full bg-border rounded-lg aspect-square"
                                :src="media.thumbnail"
                            />
                        </div>

                        <div class="grow">
                            <p class="line-clamp-1">
                                <span x-text="media.name"></span>
                            </p>

                            <p class="opacity-50 text-sm">
                                <span x-text="media.width"></span>px x
                                <span x-text="media.height"></span>px
                            </p>
                        </div>

                        <x-heroicon-o-link
                            class="w-12 h-6 cursor-pointer hover:text-primary"
                            x-on:click="window.openModal('formatter', {
                                attachment: media.id,
                                tagList: tagList,
                                isMainThumbnail: media.is_main,
                            })"
                        />

                        <x-heroicon-o-trash
                            x-show="list.length > 1"
                            class="w-12 h-6 cursor-pointer hover:text-primary"
                            x-on:click="list.splice(key, 1)"
                        />
                    </div>
                </template>
            </div>
        </x-alpine.collapsible>

        <x-alpine.collapsible title="Tags" open>
            <div class="flex flex-col gap-4">
                @foreach (($fields['tags'] ?? []) as $key => $group)
                    <div class="
                        flex items-center gap-4
                        px-4 py-3 border border-border rounded-lg bg-surface
                    ">
                        <div class="flex-grow flex flex-col">
                            <x-headers.h3 class="gap-2">
                                @if ($group['is_main'])
                                    <x-heroicon-s-bookmark class="w-4 h-4 text-primary" />
                                @endif

                                {{ $group['name'] }}
                            </x-headers.h3>

                            <p class="opacity-50">
                                Contains {{ collect($group['tags'])->filter()->count() }} tags
                            </p>
                        </div>

                        <x-heroicon-o-pencil
                            class="w-12 h-6 cursor-pointer hover:text-primary"
                            x-on:click="window.openModal('tag-group-selector', {
                                groupKey: '{{ $key }}',
                                group: {{ json_encode($group) }},
                                media: {{ json_encode($media) }},
                                uniqueNames: {{ json_encode(
                                    collect($fields['tags'] ?? [])
                                        ->pluck('name')
                                        ->except($key)
                                        ->toArray()
                                ) }},
                            })"
                        />

                        <x-heroicon-o-trash
                            class="w-12 h-6 cursor-pointer hover:text-primary"
                            x-on:click="$wire.removeTagGroup('{{ $key }}')"
                        />
                    </div>
                @endforeach

                <div class="flex gap-4">
                    <x-form.button-secondary
                        wire:click="addTagGroup"
                        text="Add new tag group"
                    />
                </div>
            </div>
        </x-alpine.collapsible>

        <div class="flex justify-end gap-4">
            <x-form.button-secondary
                wire:click="save('offline')"
                text="Archive"
            />

            <x-form.button-secondary
                wire:click="save('pending')"
                text="Save changes"
            />

            <x-form.button
                wire:click="save('online')"
                text="Publish"
            />
        </div>
    </div>
</x-container>
