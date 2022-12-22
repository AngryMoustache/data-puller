<ul {{ $attributes->only('class')->merge([
    'class' => 'flex flex-col gap-4 ' . (isset($first) ? '' : 'hidden'),
]) }}>
    @foreach ($tag->children as $child)
        <li>
            <x-form.checkbox
                wire:model.defer="fields.tags.{{ $child->id }}"
                :label="$child->nameWithCount"
                class="w-full"
            />

            @if ($child->children->isNotEmpty())
                <x-form.tag-list :tag="$child" class="pl-6" />
            @endif
        </li>
    @endforeach
</ul>
