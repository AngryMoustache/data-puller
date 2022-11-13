@props([
    'route',
    'label',
    'background' => null,
])

<li>
    <a
        style="padding: 1px"
        href="{{ $route }}"
        {{ $attributes->only('class')->merge([
            'class' => 'inline-block',
        ]) }}
    >
        <span class="
            inline-block px-10 py-2 rounded-lg {{ $background }}
            transition-all hover:bg-dark
        ">
            {{ $label }}
        </span>
    </a>
</li>
