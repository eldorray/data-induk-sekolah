<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @include('partials.favicon')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&family=Newsreader:opsz,wght@6..72,400;6..72,500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    @php
        $namaSekolah = \App\Models\SchoolSetting::get('nama_sekolah', 'Data Induk Sekolah');
        $namaYayasan = \App\Models\SchoolSetting::get('nama_yayasan', 'Yayasan Pendidikan');
    @endphp
    <div class="min-h-screen flex bg-[#f7f4ee] text-[#1f1b16]">
        <!-- Kiri: identitas -->
        <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 border-r border-[#1f1b16]/[0.08]">
            <a href="/" class="flex items-center gap-3">
                <x-app-logo size="sm" />
                <span class="text-[15px] font-semibold">{{ $namaSekolah }}</span>
            </a>
            <div class="max-w-md flex flex-col gap-4">
                <p class="text-sm text-[#7b7266]">{{ $namaYayasan }}</p>
                <h1 class="font-serif font-normal text-5xl leading-[1.05] tracking-[-0.02em]">
                    Data induk MI dan SMP
                </h1>
                <p class="text-base leading-7 text-[#5e554a]">
                    Siswa, guru, surat dinas, SK, dan laporan BOS dalam satu tempat.
                </p>
            </div>
            <p class="text-[13px] text-[#7b7266]">Khusus penggunaan internal</p>
        </div>

        <!-- Kanan: form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <div class="lg:hidden mb-8 text-center">
                    <a href="/" class="inline-flex items-center gap-3">
                        <x-app-logo size="md" />
                        <span class="font-semibold text-lg">{{ $namaSekolah }}</span>
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-[#1f1b16]/[0.08] shadow-[0_8px_24px_rgba(31,27,22,.06)] p-6 sm:p-8">
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
