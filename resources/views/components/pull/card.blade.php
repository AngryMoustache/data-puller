<div {{ $attributes->only('class')->merge([
    'class' => 'flex flex-col bg-surface bg-top rounded-xl overflow-hidden hover:scale-105 transition-all',
]) }}>
    <div class="w-full p-4 pb-0">
        <x-image class="rounded-xl aspect-square" :src="$pull->image->format('thumb')"/>
    </div>

    <div class="flex justify-between items-center p-4 px-6">
        <div class="flex flex-col w-full">
            <div class="flex gap-2">
                <p>{{ Str::limit($pull->name, 25) }}</p>
                <span class="text-dark">#{{ $pull->id }}</span>
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
