<button {{ $attributes->merge([
    'class' => '
        w-full justify-center inline-flex gap-3 items-center px-4 py-2
        font-medium rounded-md shadow-sm text-white bg-primary
        hover:bg-secondary
        hover:text-white
    '
]) }}>
    {{ $slot }}
</button>
