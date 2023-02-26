<div {{ $attributes->merge(['class' => '
    flex flex-row-reverse rounded-lg
    font-medium bg-surface-light
']) }}>
    @while ($tag->parent)
        <a
            href="{{ $tag->url() }}"
            class="px-3 py-1 border-l border-surface whitespace-nowrap hover:text-primary"
        >
            {{ $tag->name }}
        </a>

        @php $tag = $tag->parent @endphp
    @endwhile

    <a
        href="{{ $tag->url() }}"
        class="px-3 py-1 whitespace-nowrap hover:text-primary"
    >
        {{ $tag->name }}
    </a>
</div>
