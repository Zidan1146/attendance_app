<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans antialiased">
        <div class="relative flex">
            <livewire:component.sidebar />

            <!-- Main Content -->
            <div class="flex-1 p-4 transition-all duration-300" style="margin-left: 16rem">
                @yield('content')
            </div>
        </div>

    </body>
</html>
