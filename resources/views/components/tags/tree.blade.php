@php
    $classes = 'mx-6 mb-2 flex flex-col gap-4';
@endphp

<ul {{ $attributes->only('class')->merge(['class' => $classes]) }}>
    @foreach ($tags as $tag)
        @php $children = $tag->children; @endphp
        <li class="w-full text-lg flex gap-4 items-center">
            <input
                type="checkbox"
                id="tag-{{ $tag->id }}"
                value="{{ $tag->id }}"
                class="w-5 h-5"
                x-model="selections[{{ $tag->id }}]"
            />

            <label for="tag-{{ $tag->id }}">
                {{ $tag->name }}

                @if ($children->count())
                    ({{ $children->count() }})
                @endif
            </label>
        </li>

        @if ($children->count())
            <li x-show="selections[{{ $tag->id }}]" x-transition>
                <x-tags.tree
                    :tags="$children"
                    {{ $attributes->only('class')->merge(['class' => $classes . ' mb-2']) }}
                />
            </li>
        @endif
    @endforeach
</ul>
