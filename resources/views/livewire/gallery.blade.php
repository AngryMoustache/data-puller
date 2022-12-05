<x-container>
    <div
        class="py-16"
        x-data="{
            sort: @js($sort),
            display: @js($display),
            advanced: @js($bag->hasAdvanced()),
            init () {
                this.$watch('sort', (value) => {
                    @this.setSort(value)
                })

                this.$watch('display', (value) => {
                    @this.setDisplay(value)
                })
            },
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

    <x-loading-section wire:target="setSort, setDisplay">
        <x-grid.pulls :$display :$pulls />
    </x-loading-section>

    <x-loading-section class="mt-16" wire:target="addPage">
        <x-triggers.infinite-scroll :stopped="$maxPulls <= $pulls->count()" />
    </x-loading-section>
</x-container>
