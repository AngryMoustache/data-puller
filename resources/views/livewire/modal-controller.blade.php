<div @class([
    'modal-controller',
    'hidden' => ! $modal,
])>
    @if ($modal)
        <livewire:dynamic-component
            :component="'modal.' . $modal"
            :params="$params"
            :key="md5($modal . json_encode($params))"
        />
    @else
        <x-modal disable-overlay-click>
            <x-slot:main>
                <x-loading class="p-8" />
            </x-slot>
        </x-modal>
    @endif
</div>
