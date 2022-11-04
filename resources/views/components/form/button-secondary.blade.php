<button {{ $attributes->merge([
    'class' => '
        w-full justify-center inline-flex gap-3 items-center px-4 py-2
        font-medium rounded-md shadow-sm
        hover:bg-secondary
        hover:text-white
        cursor:pointer
        border border-primary text-primary bg-white hover:border-secondary
    '
]) }}>
    {{ $slot }}
</button>
