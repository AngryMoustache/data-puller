<x-container class="flex flex-col gap-12 py-12">
    <div
        class="w-full border-b border-border pb-4"
        x-data="{ open: false }"
    >
        <div class="flex items-center justify-between">
            <div
                class="flex items-center gap-4 cursor-pointer p-4 select-none"
                x-on:click="open = ! open"
                x-bind:class="{
                    'text-primary': open,
                }"
            >
                <x-heroicon-o-adjustments-horizontal x-show="! open" class="w-6 h-6" />
                <x-heroicon-s-adjustments-horizontal x-show="open" class="w-6 h-6" />
                <span>Filters</span>
            </div>

            <p class="p-4">
                {{ $count }}
                {{ Str::plural('pull', $count) }}
                found
            </p>
        </div>

        <div
            class="flex flex-col gap-4 p-4"
            x-show="open"
            x-transition
        >
            <form
                x-on:submit.prevent="search()"
                x-data="{
                    sort: @js($sort),
                    origin: @js($origin?->slug),
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
                        class="w-1/2"
                        label="Sort by"
                        :options="Sorting::list()"
                        x-model="sort"
                    />

                    <x-form.radio-list
                        class="w-1/2"
                        label="Originated from"
                        nullable
                        :options="$origins"
                        :value="$origin?->slug"
                        x-model="origin"
                    />
                </div>
            </form>
        </div>
    </div>

    <div wire:loading.remove wire:target="setFilterValues">
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

    @if ($pulls->isNotEmpty())
        <div wire:loading>
            @include('livewire.loading.grid', [
                'size' => 5,
            ])
        </div>
    @endif
</x-container>
