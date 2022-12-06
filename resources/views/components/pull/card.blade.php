<div
    x-data="{}"
    x-on:click="$wire.emit('openModal', 'pull-detail', {{ $pull->id }})"
    {{ $attributes->only('class')->merge([
        'class' => 'flex flex-col bg-surface bg-top rounded-xl overflow-hidden hover:scale-105 transition-all cursor-pointer',
    ]) }}
>
    <div class="w-full p-4 pb-0">
        <x-image class="rounded-xl aspect-square" :src="$pull->image->format('thumb')"/>
    </div>

    <x-pull.info :$pull />
</div>
