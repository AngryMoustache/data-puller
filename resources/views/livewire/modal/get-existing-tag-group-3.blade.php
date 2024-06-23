<x-modal>
    <x-slot:main class="flex flex-col gap-4" x-data>
        <x-headers.h2 text="Adding an existing tag group folder" class="p-2" />

        <div class="flex flex-col gap-4 px-2">
            <x-form.input
                label="Name"
                class="!bg-background"
                placeholder="Name of the group"
                wire:model="group.name"
            />

            <x-alpine.tabs :tabs="$tags->pluck('name', 'id')">
                @foreach ($tags as $tag)
                    <x-alpine.tab tab-key="{{ $tag->id }}">
                        <x-form.tag-tree :$tag />
                    </x-alpine.tab>
                @endforeach
            </x-alpine.tabs>
        </div>

        <div class="flex justify-between">
            <x-form.button-secondary
                text="Back to tag groups"
                x-on:click="$wire.set('step', 2)"
            />

            <x-form.button
                text="Create group"
                wire:click="confirm"
            />
        </div>
    </x-slot>
</x-modal>
