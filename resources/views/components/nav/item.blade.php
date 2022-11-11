<li>
    <a
        style="padding: 1px"
        href="{{ $route }}"
        {{ $attributes->only('class')->merge([
            'class' => 'inline-block',
        ]) }}
    >
        <span class="
            inline-block px-10 py-2 rounded-lg
            bg-background transition-all
            hover:bg-dark hover:text-background
        ">
            {{ $label }}
        </span>
    </a>
</li>
