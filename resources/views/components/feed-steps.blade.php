<ol class="flex gap-2">
    @for ($key = 1; $key <= $maxSteps; $key++)
        <li
            wire:click.prevent="gotoStep({{ $key }})"
            class="
                rounded-lg bg-dark px-4 py-2 cursor-pointer
                hover:bg-super-dark
                @if ($key > $currentStep) opacity-50 @endif
            "
        >
            {{ $key }}
        </li>
    @endfor

    <li class="w-full"></li>

    <li
        wire:click.prevent="archive({{ $key }})"
        class="
            rounded-lg bg-dark px-4 py-2 cursor-pointer
            hover:bg-super-dark
        "
    >
        Archive
    </li>

    <li
        wire:click.prevent="save({{ $key }})"
        class="
            rounded-lg bg-dark px-4 py-2 cursor-pointer
            hover:bg-super-dark
        "
    >
        Save
    </li>
</ol>
