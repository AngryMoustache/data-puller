<div>
    @dump($tags)
    @foreach ($tags as $group)
        <x-alpine.collapsible :open="true" :title="$group->name">
            <div x-show="open" class="pt-2">
                <x-form.tag-tree :tag="$group" />
            </div>
        </x-alpine.collapsible>
    @endforeach
</div>
