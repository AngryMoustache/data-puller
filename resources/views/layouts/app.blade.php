<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ env('APP_NAME') }}</title>
        <script src="https://kit.fontawesome.com/989b502037.js" crossorigin="anonymous"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <livewire:styles />
        @vite(['resources/css/app.scss', 'resources/js/app.js'])
    </head>
    <body class="pb-32">
        <div class="background"></div>
        <livewire:modal-controller />

        <x-container class="
            flex items-center justify-between w-full py-8
            flex-col sm:flex-row
        ">
            <x-headers.h1>
                <a href="/">
                    Mobile<span class="text-primary">Art</span>
                </a>
            </x-headers.h1>

            <ul class="
                flex gap-4 pt-8 sm:py-0
                flex-col sm:flex-row items-center
            ">
                <x-nav.item label="Gallery" :route="route('gallery.index')" />
                <x-nav.item-border label="Feed" :route="route('feed.index')" />
            </ul>
        </x-container>

        {{ $slot }}

        <livewire:scripts />
        <script src="{{ asset('vendor/rambo/js/index.js') }}"></script>
        @stack('rambo-scripts')
    </body>
</html>
