<x-form.button {{ $attributes->merge([
    'class' => 'border border-primary text-primary bg-transparent hover:border-secondary'
]) }}>
    {{ $slot }}
</x-form.button>
