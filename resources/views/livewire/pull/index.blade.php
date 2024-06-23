<x-container
    class="flex flex-col gap-12 py-12"
    x-data="{ extraFiltersOpen: false }"
>
    <div class="w-full">
        <div
            wire:key="extra-filters"
            class="flex flex-col md:flex-row w-full justify-between items-center border-b border-border pb-8"
        >
            <div
                class="flex items-center gap-4 cursor-pointer p-4 select-none"
                x-on:click="extraFiltersOpen = ! extraFiltersOpen"
                x-bind:class="{
                    'text-primary': extraFiltersOpen,
                }"
            >
                <x-heroicon-o-adjustments-horizontal x-show="! extraFiltersOpen" class="w-6 h-6" />
                <x-heroicon-s-adjustments-horizontal x-show="extraFiltersOpen" class="w-6 h-6" />
                <span>Extra filters</span>
            </div>

            <div class="w-fit mt-4 md:w-auto md:mt-0 flex gap-4 items-center">
                @if ($slideshow)
                    <x-form.button-secondary
                        text="Slideshow"
                        href="{{ $slideshow }}"
                    />
                @endif

                <x-form.button
                    text="Save as dynamic folder"
                    x-on:click="window.openModal('new-folder', {
                        filters: '{{ $filters->buildQueryString() }}',
                    })"
                />
            </div>
        </div>

        <div
            wire:key="extra-filters-content"
            class="flex flex-col gap-4 md:p-4"
            x-show="extraFiltersOpen"
            x-transition
        >
            <form
                x-on:submit.prevent="search()"
                x-data="{
                    origins: @js($filters->getOrigins()->pluck('key')->toArray()),
                    mediaType: @js($filters->mediaType->value),
                    sort: {
                        column: @js($filters->sorting->column->value),
                        direction: @js($filters->sorting->direction->value),
                        category: @js($filters->sorting->category),
                    },
                    init () {
                        $watch('sort', () => this.search())
                        $watch('origins', () => this.search())
                        $watch('mediaType', () => this.search())
                    },
                    search () {
                        $wire.setFilterValues(this.sort, this.origins, this.mediaType)
                    },
                }"
            >
                <div class="flex flex-col sm:flex-row gap-8 py-4 w-full">
                    <div class="flex flex-col gap-8 w-full md:w-1/2 ">
                        <div class="w-full flex flex-col gap-4">
                            <x-headers.h2>Sort by</x-headers.h2>

                            <x-form.sorting-select
                                :options="Sorting::list()"
                                :categories="$ratingCategories"
                                x-model="sort"
                            />
                        </div>

                        <x-form.radio-list
                            class="w-full !p-0"
                            label="Media type"
                            :options="MediaType::list()"
                            x-model="mediaType"
                        />
                    </div>

                    <div class="w-full md:w-1/2 flex flex-col gap-8">
                        <div class="w-full flex flex-col gap-4">
                            <x-headers.h2>Originated from</x-headers.h2>

                            @foreach ($origins as $key => $value)
                                <label class="inline-flex items-center">
                                    <input
                                        type="checkbox"
                                        value="{{ $key }}"
                                        x-model="origins"
                                        class="peer hidden"
                                    >

                                    <span class="
                                        opacity-25 cursor-pointer
                                        peer-checked:opacity-100 peer-checked:font-semibold
                                    ">
                                        {!! $value !!}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="flex gap-2 justify-between">
        <div>
            <x-form.selected-filters :$filters class="pl-4 pr-2 pb-4 md:pb-0" />
        </div>

        <p class="px-4">
            {{ $count }}
            {{ Str::plural('pull', $count) }}
            found
        </p>
    </div>

    <div wire:loading.remove wire:target="setFilterValues, toggleFilter">
        <x-alpine.infinite-scroll :enabled="$hasMore">
            @if ($pulls->isNotEmpty())
                <x-grid>
                    @foreach ($pulls as $pull)
                        <x-cards.pull wire:key="{{ $pull->id }}" :$pull />
                    @endforeach
                </x-grid>
            @else
                <p class="px-4">
                    No pulls found with those parameters!
                </p>
            @endif
        </x-alpine.infinite-scroll>
    </div>

    @if ($hasMore && $pulls->isNotEmpty())
        <div wire:loading>
            @include('livewire.loading.grid', [
                'size' => 6,
            ])
        </div>
    @endif
</x-container>
