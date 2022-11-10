<div {{ $attributes->except(['tags', 'selections'])->merge([
    'class' => 'flex flex-col gap-6 w-full',
]) }}>
    <ul class="flex w-full flex-wrap gap-2" x-show="selections.length > 0">
        <template x-for="(tag, key) in selections">
            <li class="flex gap-2 w-full items-center">
                <span
                    class="w-1/3"
                    x-text="tag.name"
                ></span>

                <x-form.input
                    class="w-full"
                    type="text"
                    x-model="tag.extra"
                    placeholder="Extra info (comma seperated)"
                />

                <i
                    x-on:click="toggle(tag)"
                    class="p-2 fa fa-trash hover:opacity-100 opacity-50 cursor-pointer"
                ></i>
            </li>
        </template>
    </ul>

    <x-form.input
        type="text"
        x-model="query"
        x-on:keydown.enter="autoSelect()"
        placeholder="Search for a tag to add"
    />

    <ul
        class="flex w-full flex-wrap gap-2"
        style="display: none"
        x-show="query.length > 0"
    >
        <template x-for="(tag, key) in filteredTags()">
            <li
                tabindex="0"
                :key="tag.id"
                x-on:click="toggle(tag)"
                x-on:keydown.enter="toggle(tag)"
            >
                <span class="
                    inline-block px-3 py-1 rounded-lg text-secondary flex items-center
                    bg-white border border-secondary
                    hover:bg-secondary hover:text-white
                    cursor-pointer text-lg
                    gap-1
                ">
                    <i class="fa fa-tag text-sm text-black-800 mr-2"></i>
                    <span x-text="tag.name"></span>
                    <span x-show="tag.extra !== ''" x-text="'- ' + tag.extra"></span>
                </span>
            </li>
        </template>

        <li
            tabindex="0"
            key="new-tag"
            x-on:click="newTag()"
            x-on:keydown.enter="newTag()"
        >
            <span class="
                inline-block px-3 py-1 rounded-lg text-secondary flex items-center
                bg-white border border-secondary
                hover:bg-secondary hover:text-white
                cursor-pointer text-lg
            ">
                <i class="fa fa-plus text-sm text-black-800 mr-2"></i>
                <span x-text="query"></span>
            </span>
        </li>
    </ul>
</div>
