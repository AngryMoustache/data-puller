<x-container class="p-8 flex flex-col gap-8 relative">
    <x-headers.h2 text="Saved tag groups" />

    <div class="w-full flex gap-12">
        <div class="w-2/3 flex flex-col gap-4">
            @forelse ($groups as $group)
                <x-lists.tag-group :$group />
            @empty
                <p class="opacity-50">No saved groups found</p>
            @endforelse
        </div>

        <div class="w-1/3 flex flex-col gap-4">
            <div class="flex">
                <x-form.button-secondary
                    text="Create new group from existing pull"
                    x-on:click.prevent="window.openModal('get-existing-tag-group')"
                />
            </div>

            <div class="flex">
                <x-form.button-secondary x-on:click.prevent="window.openModal('update-tag-group')">
                    Create new empty group
                </x-form.button-secondary>
            </div>
        </div>
    </div>
</x-container>
