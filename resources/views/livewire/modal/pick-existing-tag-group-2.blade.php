<x-modal disable-overlay-click>
    <x-slot:main>
        <div class="w-full flex flex-col gap-8 py-4">
            <x-headers.h2 text="Choose the tags to add" class="p-2" />

            <x-form.input
                label="Name"
                class="!bg-background"
                placeholder="Name of the group"
                wire:model="group.name"
            />

            <div class="border border-border rounded-xl p-4">
                @foreach ($groupModel->tags->unique()->filter(fn ($t) => ! $t->parent_id) as $tag)
                    <x-form.tag-tree
                        :$tag
                        :short-list="$groupModel->tags->pluck('id')"
                    />
                @endforeach
            </div>
        </div>
    </x-slot>

    <x-slot:footer>
        <x-form.button-secondary
            text="Back to overview"
            x-on:click="$wire.set('step', 1)"
        />

        <x-form.button-secondary
            text="Cancel"
            x-on:click="window.closeModal()"
        />

        <x-form.button
            text="Add group"
            x-on:click="$wire.call('confirm')"
        />
    </x-slot>
</x-modal>
