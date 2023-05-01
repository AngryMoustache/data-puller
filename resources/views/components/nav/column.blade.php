<div
    class="
        p-2 md:p-4
        relative w-full transition-all z-10
        border-border border-b
        md:fixed md:h-screen md:border-b-0 md:border-r
        md:w-24
    "
    x-bind:class="{
        'md:w-64': open,
        'md:w-24': ! open
    }"
>
    <div class="
        flex gap-4
        flex-row justify-between
        md:flex-col
    ">
        <span
            @click="toggle"
            class="
                pt-4 p-8 mb-4 -mx-4 border-border border-b cursor-pointer
                hidden md:block
            "
        >
            <x-heroicon-o-bars-3 class="w-8 h-6" />
        </span>

        <x-nav.item
            :route="route('home.index')"
            :active="request()->routeIs('home.index')"
            icon="heroicon-o-home"
            label="Home"
        />

        <x-nav.item
            :route="route('pull.index')"
            :active="request()->routeIs('pull.index')"
            icon="heroicon-o-archive-box"
            label="Pulls"
        />

        <x-nav.item
            :route="route('folder.index')"
            :active="request()->routeIs('folder.index')"
            icon="heroicon-o-folder"
            label="Folders"
        />

        @if ($feed)
            <x-nav.item
                :route="route('feed.index')"
                :active="request()->routeIs('feed.index')"
                icon="heroicon-o-bell-alert"
                label="Feed"
                :number="$feed"
            />
        @endif

        <x-nav.item
            :route="route('history.index')"
            :active="request()->routeIs('history.index')"
            icon="heroicon-o-clock"
            label="History"
        />
    </div>
</div>
