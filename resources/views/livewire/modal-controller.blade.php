<div class="modal-controller">
    @if ($modal)
        <livewire:dynamic-component
            :component="'modal.' . $modal"
            :params="$params"
            :key="md5($modal . json_encode($params))"
        />
    @endif
</div>
