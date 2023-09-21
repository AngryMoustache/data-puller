<x-modal disable-overlay-click full-screen>
    <x-slot:main>
        <div class="w-full flex flex-col relative md:flex-row gap-8">
            <div class="hidden-scroll w-full md:sticky md:top-4 md:w-1/3 md:h-[80vh] overflow-y-scroll">
                <livewire:feed.media-list :media="$media" />
            </div>

            <div class="w-full md:w-2/3 flex flex-col gap-8 py-4">
                <x-headers.h2 text="Group details" class="p-2" />

                <div class="flex flex-col gap-4 px-2 mb-4">
                    <x-form.input
                        wire:model="group.name"
                        label="Name"
                        class="!bg-background"
                        placeholder="Name of the tag"
                    />

                    <x-form.checkbox
                        label="Use the tags in this group for all other groups"
                        wire:model.defer="group.is_main"
                    />
                </div>

                <x-headers.h2 text="Tags" class="p-2" />

                <x-alpine.tabs :tabs="$tags->pluck('name', 'id')">
                    @foreach ($tags as $tag)
                        <x-alpine.tab tab-key="{{ $tag->id }}">
                            <x-form.tag-tree :$tag />
                        </x-alpine.tab>
                    @endforeach
                </x-alpine.tabs>

                <div class="flex">
                    <x-form.button-secondary x-on:click="document.querySelector('.modal-content').scrollTo(0, 0)">
                        Scroll to top
                    </x-form.button-secondary>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot:footer>
        <x-form.button-secondary
            text="Cancel"
            x-on:click="window.closeModal()"
        />

        <x-form.button
            text="Save"
            wire:click="save"
        />
    </x-slot>
</x-modal>
