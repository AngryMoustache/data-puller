<li
    tabindex="0"
    x-on:click="toggleTag(tag)"
    x-on:keydown.enter="toggleTag(tag)"
    class="
        inline-block px-3 py-1 rounded-lg text-secondary flex items-center
        bg-white border border-secondary
        hover:bg-secondary hover:text-white
        cursor-pointer text-lg
        gap-1
    "
>
    <i class="fa fa-tag text-sm text-black-800 mr-2"></i>
    <span x-text="tag.name"></span>
    <span x-show="tag.extra !== ''" x-text="'- ' + tag.extra"></span>
</li>
