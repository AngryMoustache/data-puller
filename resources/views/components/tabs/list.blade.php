<div {{ $attributes->except('tabs')->merge([
    'class' => 'flex gap-4 mx-4'
]) }}>
    @foreach ($tabs as $tab)
        <x-dynamic-component
            component="tabs.{{ $type }}"
            :active="$active"
            :tab="$tab"
            click="changeOrigin"
            active-class="border-blue-500"
            class="
                bg-white hover:border-blue-500 border-b-2 border-white
                cursor-pointer rounded-t-lg px-6 py-2
            "
        />
    @endforeach
</div>
