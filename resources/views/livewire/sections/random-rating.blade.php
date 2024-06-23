<div
    class="flex flex-col transition-all duration-500 ease-in-out relative"
    x-data="{
        loading: false,
        ratings: @entangle('ratings'),
        saveAndNext() {
            this.loading = true
            this.$wire.save();
        },
    }"
    x-on:saved-rating.window="loading = false"
>
    <div
        class="w-full p-4 absolute"
        x-transition
        x-bind:class="{
            'opacity-0': ! loading,
            'opacity-100': loading,
        }"
    >
        <x-loading />
    </div>

    <div
        class="w-full flex gap-12 flex-col md:flex-row"
        x-transition
        x-bind:class="{
            'opacity-0': loading,
            'opacity-100': ! loading,
        }"
    >
        <div class="w-full md:w-1/3 relative md:sticky top-8 h-fit">
            <div class="flex flex-col gap-12">
                <div class="flex flex-col gap-8">
                    @foreach ($categories as $category)
                        <x-headers.h2
                            class="!font-normal -mb-2"
                            :text="$category->name"
                        />

                        <x-form.rating
                            :category="$category"
                            :options="range(0, 10)"
                        />
                    @endforeach
                </div>

                <div class="flex flex-col items-center gap-4">
                    <x-form.button-secondary
                        text="Save rating"
                        class="text-xl w-full text-center"
                        x-on:click="saveAndNext() || window.scrollTo(0, 0)"
                    />

                    <p class="opacity-50">
                        {{ $count }} {{ Str::plural('pull', $count) }} remaining
                    </p>
                </div>
            </div>
        </div>

        <a
            class="block w-full md:w-2/3"
            href="{{ $pull->route() }}"
            target="_blank"
        >
            <livewire:feed.media-list
                wire:key="media-list-{{ $pull->id }}"
                :media="$pull->media->map->toJson()->toArray()"
            />
        </a>
    </div>
</div>
