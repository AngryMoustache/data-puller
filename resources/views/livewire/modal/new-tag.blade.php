<x-modal>
    <x-slot:main>
        <x-headers.h2 text="Tag" class="p-2" />

        <div class="flex flex-col gap-4 px-2">
            <x-form.select
                nullable
                wire:model="parent"
                label="Parent"
                :options="$tags"
                class="!bg-background"
            />

            <x-form.input
                wire:model="name"
                label="Name"
                class="!bg-background"
                placeholder="Name of the tag"
            />

            <x-form.input
                wire:model="icon"
                label="Icon (optional)"
                class="!bg-background"
                placeholder="Icon for the tag"
            />
        </div>

        <div class="flex w-full mt-4 gap-4 justify-end">
            <x-form.button-secondary
                text="Cancel"
                x-on:click="window.closeModal()"
            />

            <x-form.button
                text="Save"
                wire:click="save"
            />
        </div>
    </x-slot>
</x-modal>
