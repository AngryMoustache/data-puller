<div class="border rounded-lg overflow-hidden" x-data="{ open: false }">
    <div
        x-on:click="open = ! open"
        {{ $attributes->only('class')->merge([
            'class' => '
                flex px-4 py-2 justify-between items-center
                font-medium text-gray-700 bg-gray-100
                cursor-pointer hover:bg-gray-200
            ',
        ]) }}
    >
        {{ $title }}

        <i x-show="open" class="fas fa-chevron-down"></i>
        <i x-show="! open" class="fas fa-chevron-up"></i>
    </div>

    <div style="display: none" x-show="open" x-transition>
        {{ $slot }}
    </div>
</div>
