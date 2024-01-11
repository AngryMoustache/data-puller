<x-modal>
    <x-slot:main class="flex flex-col gap-4" x-data>
        <x-headers.h2 text="Add an existing tag group folder" class="p-2" />

        <div class="flex flex-col gap-4 px-2">
            <x-form.select
                nullable
                label="Tag group"
                :options="$groups->pluck('name', 'id')"
                class="!bg-background w-1/2"
                wire:model.live="groupId"
            />
        </div>

        @if ($groupModel)
            <div class="flex flex-col gap-4 px-2">
                <div class="border border-border rounded-xl p-4">
                    @foreach ($groupModel->tags->unique()->filter(fn ($t) => ! $t->parent_id) as $tag)
                        <x-form.tag-tree
                            :$tag
                            :short-list="$groupModel->tags->pluck('id')"
                        />
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex w-full mt-4 gap-4 justify-end">
            @if ($groupModel)
                <x-form.button-secondary
                    text="Delete group"
                    wire:click="deleteGroup"
                />
            @endif

            <x-form.button-secondary
                text="Cancel"
                x-on:click="window.closeModal()"
            />

            @if ($groupModel)
                <x-form.button
                    text="Confirm"
                    wire:click="confirm"
                />
            @endif
        </div>
    </x-slot>
</x-modal>
