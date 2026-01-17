<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">
            <!-- Left Side - Branding -->
            <div class="hidden lg:flex lg:w-1/2 gradient-mesh relative">
                <div class="absolute inset-0 flex flex-col justify-center items-center p-12">
                    <div class="max-w-md text-center animate-fade-up">
                        <div class="w-16 h-16 rounded-2xl bg-[hsl(var(--primary))] flex items-center justify-center mx-auto mb-8 shadow-lg">
                            <svg class="w-8 h-8 text-[hsl(var(--primary-foreground))]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-[hsl(var(--foreground))] mb-4">Laravel Livewire Starter Kit</h1>
                        <p class="text-[hsl(var(--muted-foreground))] text-lg">
                            Build modern, dynamic applications with PHP. Powered by Laravel, Livewire, and Tailwind CSS.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 bg-[hsl(var(--background))]">
                <div class="w-full max-w-md">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden mb-8 text-center">
                        <a href="/" class="inline-flex items-center gap-2">
                            <div class="w-10 h-10 rounded-xl bg-[hsl(var(--primary))] flex items-center justify-center">
                                <svg class="w-5 h-5 text-[hsl(var(--primary-foreground))]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <span class="font-semibold text-lg text-[hsl(var(--foreground))]">{{ config('app.name', 'Laravel') }}</span>
                        </a>
                    </div>

                    {{ $slot }}
                </div>
            </div>
        </div>

        <style>
            [x-cloak] { display: none !important; }
        </style>
    </body>
</html>
