@props([
    'tag' => null,
    'icon' => 'fa fa-tag',
])

<span {{ $attributes->only('class')->merge([
    'class' => 'inline-block px-3 py-1 rounded-lg text-black flex gap-2 items-center bg-primary'
]) }}>
    <i class="{{ $icon }} text-sm text-gray-800"></i>
    {{ $tag->name }}
</span>
