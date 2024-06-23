<div class="modal-controller">
    @foreach ($modals as $key => $modal)
        <div wire:key="{{ $key }}">
            <livewire:dynamic-component
                :component="'modal.' . $modal['modal']"
                :params="$modal['params']"
                :key="$key"
                lazy
            />
        </div>
    @endforeach
</div>
