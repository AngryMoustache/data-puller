<div {{ $attributes->merge([
    'class' => 'rounded-lg border overflow-hidden bg-white'
]) }}>
    <div class="flex items-center">
        <div
            class="flex w-10 aspect-square items-center justify-center"
            style="{{ $origin->type->style() }}"
        >
            <i class="{{ $origin->type->icon() }}"></i>
        </div>

        <strong class="px-3">
            {{ $origin->name }}
            ({{ $origin->pendingPulls->count() }})
        </strong>
    </div>

    <div class="flex flex-wrap border-t border-b">
        @foreach ($origin->pendingPulls->random(4) as $pull)
            <x-image class="w-1/2 aspect-square" :src="$pull->image->format('thumb')" />
        @endforeach
    </div>
</div>
