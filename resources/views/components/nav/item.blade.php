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
        'text-primary' => $active,
        'flex items-center p-4 text-title hover:text-primary w-full select-none transition-colors',
        'flex-col justify-center md:justify-start md:flex-row md:gap-4',
    ])
>
    <x-dynamic-component
        :component="$active ? $activeIcon : $icon"
        class="w-8 h-6"
    />

    <span x-show="open" style="display: none">
        {{ $label }}
    </span>

    <span class="block md:hidden text-sm">
        {{ $label }}
    </span>
</a>
