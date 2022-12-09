<x-form.button {{ $attributes->except('text', 'icon') }}>
    <span class="flex gap-2 items-center justify-center">
        @isset($text)
            {{ $text }}
        @endisset

        <i class="{{ $icon }}"></i>
    </span>
</x-form.button>
