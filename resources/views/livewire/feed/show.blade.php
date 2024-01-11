<x-container class="w-full flex flex-col relative md:flex-row gap-8">
    <div class="hidden-scroll w-full md:sticky md:top-2 md:w-1/3 md:min-h-feed">
        <livewire:feed.media-list :media="$media" :show-preview-generator="true" />
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

                <x-form.autocomplete
                    label="Artist"
                    placeholder="Name of the artist"
                    wire-model="fields.artist"
                    :options="$artists"
                />

                <x-form.input
                    label="Source URL"
                    placeholder="Source URL"
                    wire:model="fields.sourceUrl"
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

                            <x-heroicon-o-scissors
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
                x-on:add-to-thumbnails.window="$wire.addThumbnails([$event.detail[0]['id']])"
                x-data="{
                    list: @entangle('fields.thumbnails'),
                    tagList: @entangle('fields.tagGroups'),
                    removeThumbnail (key) {
                        this.list[key].deleted = true
                    },
                    setAsMainThumbnail (key) {
                        this.list.forEach((thumbnail, index) => {
                            thumbnail.is_main = index === key
                        })
                    },
                }"
            >
                <div class="w-full grid grid-cols-6 gap-4">
                    <template x-for="(thumbnail, key) in list" :key="'thumbnail-' + key">
                        <div
                            x-show="! thumbnail.deleted"
                            x-transition
                            class="
                                flex flex-col items-center gap-4
                                px-4 py-3 border border-border rounded-lg bg-surface
                            "
                        >
                            <div class="w-full aspect-square">
                                <img
                                    class="w-full bg-border rounded-lg aspect-square"
                                    :src="thumbnail.thumbnail_url"
                                />
                            </div>

                            <div class="w-full flex gap-4">
                                <x-heroicon-s-bookmark
                                    class="w-12 h-6 cursor-pointer text-primary hover:text-primary-dark"
                                    x-on:click="setAsMainThumbnail(key)"
                                    x-show="thumbnail.is_main"
                                />

                                <x-heroicon-o-bookmark
                                    class="w-12 h-6 cursor-pointer hover:text-primary"
                                    x-on:click="setAsMainThumbnail(key)"
                                    x-show="! thumbnail.is_main"
                                />

                                <x-heroicon-o-scissors
                                    class="w-12 h-6 cursor-pointer hover:text-primary"
                                    x-on:click="window.openModal('formatter', {
                                        thumbnailKey: key,
                                        thumbnail: thumbnail,
                                        tagList: tagList,
                                    })"
                                />

                                <x-heroicon-o-trash
                                    class="w-12 h-6 cursor-pointer hover:text-primary"
                                    x-on:click="removeThumbnail(key)"
                                />
                            </div>
                        </div>
                    </template>
                </div>

                <div class="flex gap-4">
                    <x-form.button-secondary
                        text="Add thumbnail(s)"
                        x-on:click="window.openModal('add-attachment', {
                            multiple: true,
                            target: 'add-thumbnails',
                        })"
                    />
                </div>
            </div>
        </x-alpine.collapsible>

        <x-alpine.collapsible title="Tags" open>
            <div
                class="flex flex-col gap-4"
                x-data="{
                    list: @entangle('fields.tagGroups'),
                    removeGroup (key) {
                        this.list[key].deleted  = true
                    },
                    addExistingGroup (e) {
                        group = e.detail[0]

                        this.list.push({
                            id: group.id,
                            name: group.name,
                            pull_id: group.pull_id,
                            is_main: false,
                            deleted: false,
                            tags: group.tags,
                        })
                    },
                    saveGroupAsTemplate (key) {
                        $wire.call('saveGroupAsTemplate', this.list[key].id)
                    },
                    async addGroup () {
                        const group = await $wire.createGroup()

                        this.list.push({
                            id: group.id,
                            name: group.name,
                            pull_id: group.pull_id,
                            is_main: false,
                            deleted: false,
                            tags: [],
                        })
                    },
                }"
                x-on:added-existing-group.window="(e) => addExistingGroup(e)"
            >
                <template x-for="(group, key) in list" :key="'group-' + key">
                    <div
                        x-show="! group.deleted"
                        class="
                            flex items-center gap-4
                            px-4 py-3 border border-border rounded-lg bg-surface
                        "
                    >
                        <div class="flex-grow flex flex-col">
                            <x-headers.h3 class="gap-2">
                                <x-heroicon-s-bookmark
                                    class="w-4 h-4 text-primary"
                                    x-show="group.is_main"
                                />

                                <span x-text="group.name"></span>
                            </x-headers.h3>

                            <p class="opacity-50">
                                Contains
                                <span x-text="Object.values(group.tags).filter(e => e).length"></span>
                                tags
                            </p>
                        </div>

                        <x-heroicon-o-bookmark-square
                            class="w-12 h-6 cursor-pointer hover:text-primary"
                            x-on:click="saveGroupAsTemplate(key)"
                        />

                        <x-heroicon-o-pencil
                            class="w-12 h-6 cursor-pointer hover:text-primary"
                            x-on:click="window.openModal('tag-group-selector', {
                                groupKey: key,
                                group: group,
                                media: {{ json_encode($media) }},
                            })"
                        />

                        <x-heroicon-o-trash
                            class="w-12 h-6 cursor-pointer hover:text-primary"
                            x-on:click="removeGroup(key)"
                        />
                    </div>
                </template>

                <div class="flex gap-4">
                    <x-form.button-secondary
                        text="Add empty group"
                        x-on:click="addGroup()"
                    />

                    <x-form.button-secondary
                        text="Add existing group"
                        x-on:click.prevent="window.openModal('add-existing-tag-group', {
                            pull_id: {{ $pull->id }},
                        })"
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
                text="Save as draft"
            />

            <x-form.button
                wire:click="save('online')"
                text="Save & publish"
            />
        </div>
    </div>
</x-container>
