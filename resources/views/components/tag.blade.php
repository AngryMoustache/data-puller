<span
    style="background-color: {{ $tag->color }}"
    {{ $attributes->only('class')->merge([
        'class' => 'inline-block px-3 py-1 rounded-lg text-white'
    ]) }}
>
    {{ $tag->name }}
</span>
