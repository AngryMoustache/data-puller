<div {{ $attributes->merge([
    'class' => 'w-full p-4 bg-white rounded-lg shadow-sm card'
]) }}>
    {{ $slot }}
</div>
