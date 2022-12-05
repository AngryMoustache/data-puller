<div {{ $attributes->only('class')->merge([
    'class' => 'flex flex-col bg-surface rounded-xl overflow-hidden hover:scale-105 transition-all',
]) }}>
    <x-image
        :src="$pull->image->format('thumb')"
        class="rounded-xl aspect-square"
    />
</div>
