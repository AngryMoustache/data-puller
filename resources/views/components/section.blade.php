<div {{ $attributes->only('class')->merge([
    'class' => 'w-full flex flex-col gap-8',
]) }}>
    <div class="flex flex-col gap-2">
        <x-headers.h3 :text="$title" />

        @isset($route)
            <a class="text-primary text-lg hover:underline" href="{{ $route }}">
                View all
            </a>
        @endisset
    </div>

    <div>
        {{ $slot }}
    </div>
</div>
