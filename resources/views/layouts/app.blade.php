<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Laravel Livewire 4') }}</title>
        <meta name="description" content="{{ $description ?? 'Laravel Livewire 4 Starter Kit - Build modern, dynamic applications with PHP' }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-[hsl(var(--background))]">
            {{ $slot }}
        </div>

        @livewireScripts
        
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </body>
</html>
