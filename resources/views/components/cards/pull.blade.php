<div {{ $attributes->only('class')->merge([
    'class' => 'flex flex-col bg-surface rounded-xl overflow-hidden hover:scale-105 transition-all',
]) }}>
    <div class="w-full p-4 pb-0">
        <x-image class="rounded-xl" :src="$pull->image->format('thumb')"/>
    </div>

    <div class="flex justify-between p-4">
        <div class="flex flex-col gap-1 w-full">
            <div class="flex gap-2">
                <span class="text-dark">Nr. {{ $pull->id }}</span>
                <p>{{ $pull->name }}</p>
            </div>

            <div class="opacity-50">
                {{ $pull->formattedViews }} views
            </div>
        </div>

        <div>
            @isset ($pull->origin)
                <x-origin-icon
                    :origin="$pull->origin"
                    :href="$pull->source_url"
                />
            @endisset
        </div>
    </div>
</div>
