<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ app('site')->getTitle() }}</title>
        <script src="https://kit.fontawesome.com/989b502037.js" crossorigin="anonymous"></script>
        <link rel="icon" href="{{ asset('icon.png') }}" type="image/x-icon" />
        <livewire:styles />
        @vite(['resources/css/app.scss'])
    </head>

    <body class="bg-background">
        {{ $slot }}

        <livewire:scripts />
        @vite(['resources/js/app.js'])
    </body>
</html>
