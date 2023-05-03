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
        <x-modal>
            <x-surface>
                <x-loading class="p-8" />
            </x-surface>
        </x-modal>
    @endif
</div>
