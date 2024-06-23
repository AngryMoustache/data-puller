<div x-data="{
    categories: @entangle('categories'),
    addCategory() {
        this.categories.push({ name: '' });
    },
}">
    <div class="flex p-2 w-full" wire:loading>
        <x-loading />
    </div>

    <div class="flex flex-col w-full gap-2 p-2" wire:loading.remove>
        <template x-for="(category, index) in categories" :key="index">
            <div class="flex pb-4 gap-4">
                <x-form.input
                    x-model="category.name"
                    placeholder="Category Name"
                    class="w-full"
                />

                <x-form.input
                    x-model="category.icon"
                    placeholder="Icon"
                    class="w-full"
                />

                <x-form.button-secondary
                    x-on:click="categories.splice(index, 1)"
                    class="w-fit"
                >
                    <x-heroicon-o-trash class="w-6 h-6" />
                </x-form.button-secondary>
            </div>
        </template>

        <div class="flex justify-between">
            <x-form.button-secondary
                text="Add Category"
                x-on:click="addCategory"
                class="w-fit"
            />

            <x-form.button
                text="Save changes"
                wire:click="save"
                class="w-fit"
            />
        </div>
    </div>
</div>
