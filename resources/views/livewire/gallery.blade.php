<div>
    <x-header>
        <x-headers.h1>
            Welcome to your gallery
        </x-headers.h1>
    </x-header>

    <x-container>
        @if ($origins->isNotEmpty())
            <div class="w-1/3">
                <x-headers.h3>
                    You have pulls waiting to be tagged.
                </x-headers.h3>

                <div class="flex flex-col gap-4">
                    @foreach ($origins as $origin)
                        <x-cards.origin :$origin />
                    @endforeach
                </div>
            </div>
        @endif
    </x-container>
</div>
