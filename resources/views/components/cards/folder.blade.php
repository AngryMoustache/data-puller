@props([
    'folder',
])

<a
    href="{{ $folder->route() }}"
    {{ $attributes->except('folder')->merge([
        'class' => 'bg-surface rounded p-4 p-2 w-full flex flex-col gap-2',
    ]) }}
>
    <div class="overflow-hidden rounded flex items-center" style="aspect-ratio: 3/2.5">
        <x-img
            src="{{ $folder->attachment?->format('thumb') }}"
            width="3"
            height="2.5"
        />
    </div>

    <div class="flex justify-between items-center p-1">
        <div class="flex flex-col">
            <span class="font-bold line-clamp-1">
                {{ $folder->name }}
            </span>

            <div class="text-sm opacity-75">
                {{ optional($folder->created_at)->diffForHumans() }}
            </div>
        </div>

        <div
            class="-mr-2 relative"
            x-data="{ open: false }"
        >
            <x-heroicon-o-ellipsis-vertical
                class="opacity-50 w-7 h-7"
                x-on:click.prevent="open = !open"
            />

            <ul
                x-show="open"
                x-on:mouseleave="open = false"
                x-transition
                class="
                    flex flex-col gap-1
                    p-2 absolute w-64 top-0 -right-2 rounded bg-surface z-50
                    border border-background
                "
            >
                <li
                    class="p-2 my-1 mx-1 hover:bg-border rounded flex items-center gap-4 transition-all"
                    x-on:click.prevent="window.openModal('edit-folder', {
                        id: @js($folder->id),
                        class: @js(get_class($folder)),
                        name: @js($folder->name),
                    })"
                >
                    <x-heroicon-o-pencil class="w-6 h-6" />
                    Edit name
                </li>

                <li
                    class="p-2 my-1 mx-1 hover:bg-border rounded flex items-center gap-4 transition-all"
                    x-on:click.prevent="window.openModal('delete-folder', {
                        id: @js($folder->id),
                        class: @js(get_class($folder)),
                        name: @js($folder->name),
                    })"
                >
                    <x-heroicon-o-trash class="w-6 h-6" />
                    Delete folder
                </li>
            </ul>
        </div>
    </div>
</a>
