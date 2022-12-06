<x-container>
    <form
        class="py-12"
        x-on:submit.prevent="search()"
        x-data="{
            sort: @js($sort),
            display: @js($display),
            origin: @js($origin?->slug),
            query: @entangle('query').defer,
            search () {
                @this.setSort(this.sort)
                @this.setDisplay(this.display)
                @this.setOrigin(this.origin)
            },
        }"
    >
        <div class="flex justify-between gap-4 items-center">
            <div class="w-full">
                <x-filter.query
                    :value="$query"
                    x-model="query"
                />
            </div>
        </div>

        <div class="py-4 flex gap-4 w-full">
            <x-form.select
                nullable
                label="Origin"
                :options="$origins"
                :value="$origin?->slug"
                x-model="origin"
            />

            <x-form.select
                label="Sort order"
                :options="Sorting::list()"
                x-model="sort"
            />

            <x-form.select
                label="Display type"
                :options="Display::list()"
                x-model="display"
            />

            <x-form.button class="px-4 py-2 text-sm" x-on:click="search()">
                <i class="fas fa-search"></i>
            </x-form.button>
        </div>
    </form>

    <x-loading-section wire:target="setSort, setDisplay">
        <x-grid.pulls :$display :$pulls />
    </x-loading-section>

    <x-loading-section class="mt-16" wire:target="addPage">
        <x-triggers.infinite-scroll :stopped="$maxPulls <= $pulls->count()" />
    </x-loading-section>

    <script>
        // Update the URL with livewire event
        window.addEventListener('update-url', (e) => {
            history.pushState(null, null, e.detail.url)
        })
    </script>
</x-container>
