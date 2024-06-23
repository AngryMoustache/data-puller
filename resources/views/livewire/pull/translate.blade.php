<div class="container mx-auto p-4 py-6 flex-col gap-4" x-data="{
    pickLocation (event) {
        const mouseX = event.clientX - this.$el.getBoundingClientRect().left
        const mouseY = event.clientY - this.$el.getBoundingClientRect().top
        $wire.call('newTranslation', {
            x: (mouseX / this.$el.offsetWidth * 100),
            y: (mouseY / this.$el.offsetHeight * 100),
        })
    },
}">
    <div class="w-full flex items-center justify-between gap-2 mb-4">
        <div class="flex-col gap-2">
            <x-headers.h1 text="Translating {{ $pull->name }}" />
            <p>{{ $translations->count() }} {{ Str::plural('translation', $translations->count()) }}</p>
        </div>

        <div class="flex gap-4">
            <x-form.button-secondary
                text="Back to detail"
                href="{{ $pull->route() }}"
            />
        </div>
    </div>

    <div class="w-full flex gap-4">
        <div class="w-1/5 flex-col gap-4">
            <div class="flex flex-col gap-2">
                @foreach ($media as $key => $item)
                    <div
                        x-on:click="$wire.setCurrent({{ $item->id }})"
                        @class([
                            'flex items-center gap-4',
                            'p-2 border border-border rounded-lg bg-surface',
                            'border-primary' => $current->is($item),
                        ])
                    >
                        <div class="w-16 h-16">
                            <img
                                class="w-full bg-border rounded-lg aspect-square"
                                src="{{ $item->format('thumb') }}"
                            />
                        </div>

                        <div class="grow">
                            <p class="line-clamp-1">
                                Image #{{ $key + 1 }}
                            </p>

                            <p class="opacity-50 text-sm">
                                {{ $item->translations->count() }}
                                {{ Str::plural('translation', $item->translations->count()) }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="w-4/5">
            <div class="w-full relative">
                @foreach ($translations as $translation)
                    @if ($translation['location'])
                        <span
                            x-on:click.prevent="window.openModal('edit-translation', {
                                translationId: {{ $translation->id }},
                            })"
                            class="
                                absolute w-12 h-12 rounded-full z-10 p-2
                                bg-black bg-opacity-75 border border-primary
                                text-white text-opacity-75 text-center font-bold text-xl
                                cursor-pointer hover:bg-dark
                            "
                            style="
                                left: calc({{ $translation['location']['x'] }}% - 1.5rem);
                                top: calc({{ $translation['location']['y'] }}% - 1.5rem);
                            "
                        >
                            {{ $loop->index + 1 }}
                        </span>
                    @endif
                @endforeach

                <div x-on:click="pickLocation" class="w-full">
                    <x-img
                        wire:key="image-list-{{ $current->id }}"
                        class="rounded"
                        :src="$current->path()"
                        :width="$current->width"
                        :height="$current->height"
                    />
                </div>
            </div>
        </div>
    </div>
</div>
