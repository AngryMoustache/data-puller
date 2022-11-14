<x-container>
    <div
        class="py-16"
        x-data="{
            advanced: @js($bag->hasAdvanced()),
            sort: @entangle('sort'),
            display: @entangle('display'),
        }"
    >
        <div class="flex justify-between gap-4 items-center">
            <div class="w-full">
                <x-filter.query :value="$bag->filters['query']" />
            </div>

            <x-form.button class="px-3 py-2 text-sm" x-on:click.prevent="advanced = ! advanced">
                <i class="fas fa-cogs"></i>
            </x-form.button>
        </div>

        <div
            x-show="advanced"
            class="py-4 flex gap-4 w-full"
            x-transition
        >
            <x-form.select
                label="Sort order"
                :options="$bag->sortOptions()"
                x-model="sort"
            />

            <x-form.select
                label="Display type"
                :options="$bag->displayOptions()"
                x-model="display"
            />
        </div>
    </div>

    <div wire:loading class="w-full">
        <x-loading />
    </div>

    <div wire:loading.remove>
        <x-grid.pulls :$display :$pulls />
    </div>
</x-container>
