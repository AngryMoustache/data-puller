<div {{ $attributes->merge(['class' => '
    grid
    grid-cols-2 md:grid-cols-4 lg:grid-cols-6
    gap-2 md:gap-4
']) }}>
    {{ $slot }}
</div>
