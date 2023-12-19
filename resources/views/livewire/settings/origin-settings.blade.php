<div class="flex flex-col gap-2">
    @foreach ($origins as $key => $origin)
        <div class="w-full flex items-center gap-2">
            <x-form.checkbox wire:model="origins.{{ $key }}.online" />
            {!! $origin['blade'] !!}
        </div>
    @endforeach

    <div class="w-full flex">
        <x-form.button
            text="Save origins"
            wire:click="save"
            class="mt-4"
        />
    </div>
</div>
