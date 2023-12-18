<div {{ $attributes->except('tag') }} x-data="{
    open{{ $tag->id }}: $wire.get('group.tags.{{ $tag->id }}'),
}">
    <div class="flex items-center">
        <x-form.checkbox
            wire:model.defer="group.tags.{{ $tag->id }}"
            x-model="open{{ $tag->id }}"
        >
            <span class="flex flex-col">
                <span>{{ $tag->name }}</span>

                @if ($tag->children->isNotEmpty())
                    <span class="opacity-25 text-sm -mt-1">
                        Has
                        {{ $tag->children->count() }}
                        {{ Str::plural('child', $tag->children->count()) }}
                    </span>
                @endif
            </span>
        </x-form.checkbox>

        <span
            class="ml-2 p-2 text-xl text-primary cursor-pointer rounded hover:bg-dark-hover hover:text-text"
            x-on:click="window.openModal('edit-tag', {
                id: @js($tag->id),
                name: @js($tag->name),
                icon: @js($tag->icon),
                parent: @js($tag->parent_id),
            })"
        >
            <x-heroicon-o-pencil class="w-4 h-4" />
        </span>

        <span
            class="ml-2 p-2 text-xl text-primary cursor-pointer rounded hover:bg-dark-hover hover:text-text"
            x-on:click="window.openModal('new-tag', {
                parent: {{ $tag->id }},
            })"
        >
            <x-heroicon-o-plus class="w-4 h-4" />
        </span>
    </div>

    @if ($tag->children->isNotEmpty())
        <div class="px-6 pt-2 flex flex-col gap-2" x-show="open{{ $tag->id }}">
            @foreach ($tag->children as $tag)
                <x-form.tag-tree :$tag />
            @endforeach
        </div>
    @endif
</div>
