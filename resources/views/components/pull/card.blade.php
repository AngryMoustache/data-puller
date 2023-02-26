<div {{ $attributes->only('class')->merge([
    'class' => 'flex flex-col bg-surface bg-top rounded-xl overflow-hidden
        hover:scale-95 transition-all cursor-pointer',
]) }}>
    <a href="{{ $pull->url() }}" class="w-full p-4 pb-0">
        <x-image class="rounded-xl aspect-square" :src="$pull->image->format('thumb')"/>
    </a>

    <x-pull.info :$pull />
</div>
