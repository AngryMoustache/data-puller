@props([
    'tabs' => [0 => 'General'],
    'activeTab' => \Illuminate\Support\Collection::wrap($tabs)->keys()->first(),
])

<div
    {{ $attributes->merge(['class' => 'border border-border rounded-xl']) }}
    x-data="{ activeTab: @js((string) $activeTab) }"
>
    <ul class="p-4 flex gap-4 border-b border-border">
        @foreach ($tabs as $key => $label)
            <li
                x-on:click="activeTab = '{{ $key }}'"
                class="cursor-pointer py-2 px-4 rounded-xl hover:bg-dark-border transition-all"
                x-bind:class="{
                    'bg-dark-border': activeTab === '{{ $key }}',
                }"
            >
                {{ $label }}
            </li>
        @endforeach
    </ul>

    {{ $slot }}
</div>
