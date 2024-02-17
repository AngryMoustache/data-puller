@props([
    'group',
    'buttons' => true,
])

<div {{ $attributes->merge(['class' => '
    flex items-center gap-8
    px-4 py-3 border border-border rounded-lg bg-surface
']) }}>
    {{-- <div class="w-32 overflow-hidden rounded" style="aspect-ratio: 3/2.5">
        <x-img
            src="{{ $group->attachment?->format('thumb') }}"
            :width="3"
            :height="2.5"
        />
    </div> --}}

    <div class="flex gap-2 items-center justify-between w-full">
        <div class="flex flex-col">
            <x-headers.h3 :text="$group->name" />

            <p class="opacity-50">
                Contains {{ $group->tags()->count() }} tags
            </p>
        </div>

        @if ($buttons)
            <div class="flex gap-4">
                <x-form.button
                    class="flex gap-2 items-center"
                    x-on:click.prevent="window.openModal('update-tag-group', { id: {{ $group->id }} })"
                >
                    Edit <x-heroicon-o-pencil class="w-5 h-5" />
                </x-form.button>

                <x-form.button-secondary
                    class="flex gap-2 items-center"
                    x-on:click.prevent="$wire.call('deleteGroup', {{ $group->id }})"
                >
                    Delete <x-heroicon-o-trash class="w-5 h-5" />
                </x-form.button-secondary>
            </div>
        @endif
    </div>
</div>
