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
        overflow-x-auto md:overflow-x-hidden
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
            icon="heroicon-o-inbox-arrow-down"
            label="Pulls"
        />

        <x-nav.item
            :route="route('folder.index')"
            :active="request()->routeIs('folder.index')"
            icon="heroicon-o-folder"
            label="Folders"
        />

        <x-nav.item
            :route="route('pull.random')"
            :active="request()->routeIs('pull.random')"
            icon="heroicon-o-arrow-path-rounded-square"
            label="Random"
        />

        <x-nav.item
            :route="route('feed.index')"
            :active="request()->routeIs('feed.index')"
            icon="heroicon-o-bell-alert"
            label="Feed"
            :number="$feed"
        />

        {{-- <x-nav.item
            :route="route('prompt.index')"
            :active="request()->routeIs('prompt.index')"
            icon="heroicon-o-pencil"
            label="Prompts"
        /> --}}

        <x-nav.item
            :route="route('history.index')"
            :active="request()->routeIs('history.index')"
            icon="heroicon-o-clock"
            label="History"
        />

        <x-nav.item
            :route="route('settings.index')"
            :active="request()->routeIs('settings.index')"
            icon="heroicon-o-wrench-screwdriver"
            label="Settings"
        />
    </div>
</div>
