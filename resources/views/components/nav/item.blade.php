@props([
    'route' => '#',
    'icon' => '',
    'activeIcon' => Str::replace('-o-', '-s-', $icon),
    'active' => isset($route) && ($route === request()->url()),
    'label',
    'number' => null,
])

<a
    href="{{ $route }}"
    {{ $attributes->only('x-on:click.prevent') }}
    @class([
        'text-primary' => $active,
        'flex items-center p-4 text-title hover:text-primary w-full select-none transition-colors',
        'flex-col justify-center md:justify-start md:flex-row md:gap-4 relative',
    ])
>
    <div class="relative w-8 h-6">
        <x-dynamic-component
            :component="$active ? $activeIcon : $icon"
            class="w-8 h-6"
        />

        @if ($number)
            <span class="
                absolute -right-2 -top-3 rounded-full px-2 py-1
                text-white bg-primary text-xs
                border-2 border-background
            ">
                {{ $number }}
            </span>
        @endif
    </div>

    <span x-show="open" style="display: none">
        {{ $label }}
    </span>

    <span class="block md:hidden text-sm">
        {{ $label }}
    </span>
</a>
