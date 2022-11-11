<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ env('APP_NAME') }}</title>
        <script src="https://kit.fontawesome.com/989b502037.js" crossorigin="anonymous"></script>
        <livewire:styles />
        @vite(['resources/css/app.scss', 'resources/js/app.js'])
    </head>
    <body class="pb-32">
        <div class="background"></div>

        <x-container class="flex items-center justify-between w-full py-8">
            <x-headers.h1>
                Mobile<span class="text-primary">Art</span>
            </x-headers.h1>

            <ul class="flex gap-4">
                <x-nav.item label="Gallery" :route="route('gallery.index')" />
                <x-nav.item label="Tags" :route="route('tag-manager.index')" />
                <x-nav.item-border label="Feed" :route="route('feed.index')" />
            </ul>
        </x-container>

        {{ $slot }}

        <livewire:scripts />
    </body>
</html>
