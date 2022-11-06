<div>
    <x-header>
        <x-headers.h1>You have some new pulls!</x-headers.h1>
    </x-header>

    <x-container>
        @if ($pull)
            <x-loading-card
                wire:target="savePull, saveSelections, changeOrigin"
                class="flex gap-6 p-6"
                wire:key="pull_{{ $pull->id }}"
            >
                <div class="w-1/2 flex flex-col gap-4" x-data="{
                    media: @js($pull->jsonAttachments),
                    removeAttachment (id) {
                        this.media = this.media.filter((item) => item.id !== id)
                    },
                }">
                    {{-- Controls --}}
                    <x-collapse title="Media controls">
                        <div class="w-full" wire:loading>
                            <x-loading />
                        </div>

                        <div wire:loading.remove>
                            <ul class="grid grid-cols-3 gap-4 p-4">
                                <template x-for="item in media">
                                    <li class="flex items-center relative" :key="item.path">
                                        <div class="w-full flex gap-2 absolute top-2 left-2" x-show="media.length > 1">
                                            <x-form.button class="w-8">
                                                <i class=" fas fa-arrows-alt"></i>
                                            </x-form.button>

                                            <x-form.button class="w-8" x-on:click="removeAttachment(item.id)">
                                                <i class=" fas fa-trash"></i>
                                            </x-form.button>
                                        </div>

                                        <img
                                            class="w-full border rounded-lg shadow-sm"
                                            :src="item.path"
                                        >
                                    </li>
                                </template>
                            </ul>

                            <div class="p-4 pt-0">
                                <x-form.button-secondary x-on:click="$wire.updateAttachments(media)">
                                    <i class=" fas fa-save"></i>
                                    Save changes
                                </x-form.button-secondary>
                            </div>
                        </div>
                    </x-collapse>

                    @foreach ($pull->attachments as $image)
                        <x-image :src="$image->path()" />
                    @endforeach

                    @foreach ($pull->videos as $video)
                        <x-video :src="$video->path()" />
                    @endforeach
                </div>

                <div class="w-1/2" x-data="{
                    {{-- Name updating --}}
                    name: @js($pull->name),
                    oldName: @js($pull->name),
                    updateName () {
                        $wire.updateName(this.name)
                        this.oldName = this.name
                    },

                    {{-- Tag selection --}}
                    query: '',
                    tags: @js($tags),
                    selections: @js($selections),
                    toggle (tag) {
                        if (this.selections.includes(tag)) {
                            this.selections = this.selections.filter(t => t !== tag)
                        } else {
                            this.selections.push(tag)
                        }
                    },
                    filteredTags () {
                        return this.tags.filter((tag) => {
                            return ! this.selections.includes(tag)
                                && (tag.name + tag.extra).toLowerCase().includes(this.query.toLowerCase())
                        }).slice(0, 20)
                    },
                    newTag () {
                        this.selections.push({ name: this.query, extra: null })
                        this.query = ''
                    },
                    autoSelect () {
                        if (this.filteredTags().length > 0) {
                            this.toggle(this.filteredTags()[0])
                        } else {
                            this.newTag()
                        }

                        this.query = ''
                    },

                    {{-- Save data --}}
                    saveSelections () {
                        @this.call('saveSelections', this.selections)
                    }
                }">
                    <x-headers.h3 class="pb-0 flex items-center">
                        <input
                            placeholder="Pull name"
                            type="text"
                            class="w-full focus:outline-none py-2"
                            x-model="name"
                        >

                        <i x-show="oldName !== name" x-on:click="updateName" class="
                            border ml-2 rounded-lg px-4 py-2 cursor-pointer
                            hover:bg-gray-100
                            far fa-save
                        "></i>
                    </x-headers.h3>

                    <div class="w-full mt-3 mb-6 border-t"></div>

                    <div class="flex flex-col gap-4">
                        <x-tag-selector :$tags :$selections />

                        <div class="w-full my-4 border-t"></div>

                        <div class="flex gap-4 w-full">
                            <x-form.button-secondary x-on:click="$wire.savePull('offline')">
                                <i class="fa fa-times"></i>
                                Archive pull
                            </x-form.button-secondary>

                            <x-form.button x-on:click="saveSelections()">
                                <i class="far fa-save"></i>
                                Save and pull
                            </x-form.button>
                        </div>
                    </div>
                </div>
            </x-loading-card>
        @else
            <x-card class="p-6">
                <x-headers.h3 class="pb-0">No new items to pull!</x-headers.h3>
            </x-card>
        @endif
    </x-container>
</div>
