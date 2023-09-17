<x-modal disable-overlay-click>
    <x-slot:main>
        <x-headers.h2 text="Group details" class="p-2" />

        <div class="flex flex-col gap-4 px-2 mb-4">
            <x-form.input
                wire:model="groupName"
                label="Name"
                class="!bg-background"
                placeholder="Name of the tag"
            />

            <x-form.checkbox
                label="Use the tags in this group for all other groups"
                wire:model.defer="isMain"
            />
        </div>

        <x-headers.h2 text="Tags" class="p-2" />

        @foreach ($tags as $tag)
            <x-alpine.collapsible
                :open="$selectedTags[$tag->id] ?? false"
                :title="$tag->name"
            >
                <div x-show="open" class="pt-2">
                    <x-form.tag-tree :$tag />
                </div>
            </x-alpine.collapsible>
        @endforeach
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
