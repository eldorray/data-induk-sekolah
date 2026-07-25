<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @include('partials.favicon')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden" style="background: #fbfbfd;">
            <div class="glow-blob bg-blue-200" style="width: 420px; height: 420px; top: -120px; left: -80px;"></div>
            <div class="glow-blob bg-blue-100" style="width: 360px; height: 360px; bottom: -140px; right: -60px;"></div>
            <div class="absolute inset-0 flex flex-col justify-center items-center p-12">
                <div class="max-w-md text-center animate-fade-up relative">
                    <div class="flex justify-center mb-8">
                        <x-app-logo size="lg" class="shadow-lg" />
                    </div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 mb-4">
                        Data Induk Sekolah <span class="text-gradient-blue">MI &amp; SMP</span>
                    </h1>
                    <p class="text-[hsl(var(--muted-foreground))] text-lg">
                        Sistem manajemen data sekolah terintegrasi untuk mengelola data Siswa, Guru, dan Mata Pelajaran
                        MI &amp; SMP.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 bg-white">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-8 text-center">
                    <a href="/" class="inline-flex items-center gap-2">
                        <x-app-logo size="md" />
                        <span class="font-semibold text-lg text-[hsl(var(--foreground))]">Data Induk Sekolah</span>
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</body>

</html>
