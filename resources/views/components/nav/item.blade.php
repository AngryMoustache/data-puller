@props([
    'route',
    'icon' => '',
    'activeIcon' => Str::replace('-o-', '-s-', $icon),
    'active' => $route === request()->url(),
    'label',
])

<a
    href="{{ $route }}"
    @class([
        'flex items-center gap-4 p-4 text-title hover:text-primary w-full select-none transition-colors',
        'text-primary' => $active,
    ])
>
    <x-dynamic-component
        :component="$active ? $activeIcon : $icon"
        class="w-8 h-6"
    />

    <span x-show="open" style="display: none">
        {{ $label }}
    </span>
</a>
