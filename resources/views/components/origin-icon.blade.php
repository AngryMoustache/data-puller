<a
    style="{{ $origin->type->style() }}"
    {{ $attributes->except('origin')->merge([
        'class' => 'flex items-center justify-center rounded-lg w-8 h-8',
        'target' => '_blank',
    ]) }}
>
    <i class="{{ $origin->type->icon() }}"></i>
</a>
