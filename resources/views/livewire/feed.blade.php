<div>
    <x-header>
        <x-headers.h1>You have some new pulls!</x-headers.h1>
    </x-header>

    <x-container>
        @if ($active)
            <x-tabs.list
                wire:loading.remove
                wire:target="savePull, saveSelections"
                :tabs="$origins"
                :active="$active->id"
                type="origin"
                click="changeOrigin"
            />

            <x-loading-card wire:target="savePull, saveSelections" class="flex gap-6 p-6" wire:key="pull_{{ $pull->id }}">
                <div class="w-1/2 flex flex-col gap-4">
                    @foreach ($pull->attachments as $image)
                        <x-image :src="$image->path()" />
                    @endforeach

                    @foreach ($pull->videos as $video)
                        <x-video :src="$video->path()" />
                    @endforeach
                </div>

                <div class="w-1/2" x-data="{
                    selections: @entangle('selections').defer,
                    name: @js($pull->name),
                    oldName: @js($pull->name),
                    updateName () {
                        $wire.updateName(this.name)
                        this.oldName = this.name
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
                        @foreach ($tags as $tag)
                            <x-collapse :title="$tag->name">
                                <div class="my-4">
                                    <x-tags.tree :tags="$tag->children" />
                                </div>
                            </x-collapse>
                        @endforeach

                        <div class="w-full my-4 border-t"></div>

                        <div class="flex gap-4 w-full">
                            <x-form.button-secondary x-on:click="$wire.savePull('offline')">
                                <i class="fa fa-times"></i>
                                Ignore pull
                            </x-form.button-secondary>

                            <x-form.button x-on:click="$wire.saveSelections()">
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
