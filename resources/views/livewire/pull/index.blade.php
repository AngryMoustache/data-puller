<x-container class="flex flex-col gap-12 py-12">
    @if ($filters->filters->isNotEmpty())
        <div class="pb-4 md:pb-0 md:flex justify-between gap-4 pl-4 pr-2">
            <div class="w-full md:w-fit flex flex-wrap gap-4">
                @foreach ($filters->filters as $filter)
                    <x-tag
                        wire:key="enabled-tag-{{ $filter->key }}"
                        class="flex gap-3 !py-2 !px-4 !text-base"
                        wire:click="toggleFilter({{ json_encode($filter->type) }}, {{ $filter->id }})"
                    >
                        @switch ($filter->type)
                            @case (App\Models\Tag::class) <x-heroicon-o-tag class="w-5 h-5" /> @break
                            @case (App\Models\Artist::class) <x-heroicon-o-user-group class="w-5 h-5" /> @break
                            @case (App\Models\Folder::class) <x-heroicon-o-folder-open class="w-5 h-5" /> @break
                            @case ('query') <x-heroicon-o-magnifying-glass class="w-5 h-5" /> @break
                        @endswitch

                        {{ $filter->value }}
                    </x-tag>
                @endforeach
            </div>

            <div class="w-fit mt-4 md:w-auto md:mt-0">
                <x-form.button
                    text="Save as dynamic folder"
                    x-on:click="window.openModal('new-folder', {
                        filters: '{{ $filters->buildQueryString() }}',
                    })"
                />
            </div>
        </div>
    @endif

    <div
        class="w-full border-b border-border pb-8"
        x-data="{ extraFiltersOpen: false }"
    >
        <div class="flex items-center justify-between">
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

            <p class="p-4">
                {{ $count }}
                {{ Str::plural('pull', $count) }}
                found
            </p>
        </div>

        <div
            class="flex flex-col gap-4 md:p-4"
            x-show="extraFiltersOpen"
            x-transition
        >
            <form
                x-on:submit.prevent="search()"
                x-data="{
                    sort: @js($filters->sort->value),
                    origin: @js($filters->getOrigin()?->key),
                    init () {
                        $watch('sort', () => this.search())
                        $watch('origin', () => this.search())
                    },
                    search () {
                        $wire.setFilterValues(this.sort, this.origin)
                    },
                }"
            >
                <div class="flex flex-col sm:flex-row gap-8 py-4 w-full">
                    <x-form.radio-list
                        class="w-full md:w-1/2"
                        label="Sort by"
                        :options="Sorting::list()"
                        x-model="sort"
                    />

                    <x-form.radio-list
                        class="w-full md:w-1/2"
                        label="Originated from"
                        nullable
                        :options="$origins"
                        :value="$filters->getOrigin()?->key"
                        x-model="origin"
                    />
                </div>
            </form>
        </div>
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
