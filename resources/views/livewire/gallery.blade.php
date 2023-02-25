<x-container>
    <form
        class="py-12"
        x-on:submit.prevent="search()"
        x-data="{
            sort: @js($sort),
            display: @js($display),
            origin: @js($origin?->slug),
            query: @entangle('query').defer,
            init () {
                $watch('sort', () => this.search())
                $watch('display', () => this.search())
                $watch('origin', () => this.search())
            },
            search () {
                $wire.setFilterValues(this.sort, this.display, this.origin)
            },
        }"
    >
        <div class=" flex flex-col sm:flex-row gap-4 py-4 w-full">
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
        </div>

        <div class="flex justify-between gap-4 items-center">
            <x-filter.query
                :value="$query"
                x-model="query"
            />
        </div>
    </form>

    <x-loading-section wire:target="setFilterValues">
        <x-grid.pulls :$display :$pulls />

        <x-loading-section class="mt-16" wire:target="addPage">
            <x-triggers.infinite-scroll :stopped="$maxPulls <= $pulls->count()" />
        </x-loading-section>
    </x-loading-section>

    <script>
        // Update the URL with livewire event
        window.addEventListener('update-url', (e) => {
            history.pushState(null, null, e.detail.url)
        })
    </script>
</x-container>
