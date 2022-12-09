<span {{ $attributes->except('tag')->merge([
    'class' => 'inline-block rounded-lg px-4 py-1 border'
]) }}>
    {{ $slot }}
    {{ $tag->name }}
</span>
