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
    <body>
        <div class="background"></div>

        <livewire:modal-controller />

        <x-main>
            {{ $slot }}
        </x-main>

        <livewire:scripts />
    </body>
</html>
