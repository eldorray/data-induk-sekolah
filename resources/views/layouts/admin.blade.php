<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($header) ? strip_tags((string) $header) . ' · ' : '' }}Admin - {{ config('app.name', 'Laravel') }}</title>

    @include('partials.favicon')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&family=Newsreader:opsz,wght@6..72,400;6..72,500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

@php
    $isAdmin = auth()->user()->isAdmin();

    // Ikon garis (lucide), stroke 1.5. Konstanta tepercaya, dirender sebagai SVG.
    $icons = [
        'home' => '<path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6h-6v6H4a1 1 0 0 1-1-1z"/>',
        'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'cap' => '<path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 2 9 2 12 0v-5"/>',
        'check' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
        'award' => '<circle cx="12" cy="8" r="6"/><path d="M15.5 13.5 17 22l-5-3-5 3 1.5-8.5"/>',
        'file' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8"/>',
        'pen' => '<path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
        'receipt' => '<path d="M4 2v20l3-2 3 2 3-2 3 2 3-2 1 1V2l-1 1-3-2-3 2-3-2-3 2z"/><path d="M8 8h8M8 12h8M8 16h5"/>',
        'clipboard' => '<rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2M9 14l2 2 4-4"/>',
        'history' => '<path d="M3 12a9 9 0 1 0 9-9M3 4v5h5"/><path d="M12 7v5l3 2"/>',
        'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        'sliders' => '<path d="M4 6h10M18 6h2M4 12h4M12 12h8M4 18h12"/><circle cx="16" cy="6" r="2"/><circle cx="10" cy="12" r="2"/><circle cx="18" cy="18" r="2"/>',
        'key' => '<circle cx="7.5" cy="15.5" r="5.5"/><path d="m21 2-9.6 9.6M15.5 7.5l3 3L22 7l-3-3"/>',
    ];

    // Struktur menu. 'admin' => true berarti hanya untuk admin; izin tetap dicek di route.
    $sections = [
        [
            'label' => null,
            'items' => [
                ['label' => 'Dashboard', 'route' => 'dashboard', 'match' => ['dashboard'], 'icon' => 'home', 'admin' => true],
            ],
        ],
        [
            'label' => 'Data induk',
            'items' => [
                ['label' => 'Siswa, guru, mapel', 'icon' => 'users', 'admin' => true, 'children' => [
                    ['label' => 'Siswa MI', 'route' => 'siswa-mi.index', 'match' => ['siswa-mi.*']],
                    ['label' => 'Siswa SMP', 'route' => 'siswa-smp.index', 'match' => ['siswa-smp.*']],
                    ['label' => 'Guru MI', 'route' => 'guru-mi.index', 'match' => ['guru-mi.*']],
                    ['label' => 'Guru SMP', 'route' => 'guru-smp.index', 'match' => ['guru-smp.*']],
                    ['label' => 'Mapel MI', 'route' => 'mapel-mi.index', 'match' => ['mapel-mi.*']],
                    ['label' => 'Mapel SMP', 'route' => 'mapel-smp.index', 'match' => ['mapel-smp.*']],
                ]],
                ['label' => 'Nilai ijazah kelas 6', 'route' => 'nilai-ijazah.index', 'match' => ['nilai-ijazah.*'], 'icon' => 'cap', 'admin' => false],
                ['label' => 'Validasi data siswa', 'route' => 'validasi-data-siswa.index', 'match' => ['validasi-data-siswa.index'], 'icon' => 'check', 'admin' => true],
            ],
        ],
        [
            'label' => 'Administrasi',
            'items' => [
                ['label' => 'SK guru', 'icon' => 'award', 'admin' => true, 'children' => [
                    ['label' => 'SK GTY MI', 'route' => 'sk-gty-mi.index', 'match' => ['sk-gty-mi.*']],
                    ['label' => 'SK tugas tambahan', 'route' => 'sk-tugas-tambahan-mi.index', 'match' => ['sk-tugas-tambahan-mi.*']],
                    ['label' => 'Pembagian tugas', 'route' => 'sk-pembagian-tugas-mi.index', 'match' => ['sk-pembagian-tugas-mi.*']],
                ]],
                ['label' => 'Surat siswa', 'icon' => 'file', 'admin' => true, 'children' => [
                    ['label' => 'Mutasi', 'route' => 'mutasi.index', 'match' => ['mutasi.*']],
                    ['label' => 'Surat aktif', 'route' => 'surat-aktif.index', 'match' => ['surat-aktif.*']],
                    ['label' => 'Surat universal', 'route' => 'surat-universal.index', 'match' => ['surat-universal.*']],
                    ['label' => 'Rekap PKH', 'route' => 'surat-rekap-pkh.index', 'match' => ['surat-rekap-pkh.*']],
                    ['label' => 'Terima siswa pindahan', 'route' => 'surat-terima-pindahan.index', 'match' => ['surat-terima-pindahan.*']],
                    ['label' => 'Syarat pindahan', 'route' => 'syarat-pindahan.index', 'match' => ['syarat-pindahan.*']],
                ]],
                ['label' => 'Surat pernyataan', 'icon' => 'pen', 'admin' => true, 'children' => [
                    ['label' => 'Insentif', 'route' => 'surat-pernyataan-insentif.index', 'match' => ['surat-pernyataan-insentif.*']],
                    ['label' => 'Tangcer', 'route' => 'surat-pernyataan-tangcer.index', 'match' => ['surat-pernyataan-tangcer.*']],
                    ['label' => 'Rombel', 'route' => 'surat-pernyataan-rombel.index', 'match' => ['surat-pernyataan-rombel.*']],
                ]],
                ['label' => 'BOS', 'icon' => 'receipt', 'admin' => true, 'children' => [
                    ['label' => 'Kuitansi BOS', 'route' => 'kuitansi-bos.index', 'match' => ['kuitansi-bos.*']],
                    ['label' => 'LPJ BOS', 'route' => 'lpj-bos.index', 'match' => ['lpj-bos.*']],
                ]],
                ['label' => 'LPJ insentif', 'icon' => 'clipboard', 'admin' => true, 'children' => [
                    ['label' => 'LPJ insentif', 'route' => 'lpj-insentif.index', 'match' => ['lpj-insentif.*']],
                    ['label' => 'Absensi insentif', 'route' => 'absensi-insentif.index', 'match' => ['absensi-insentif.*']],
                ]],
                ['label' => 'Tracer alumni', 'route' => 'tracer-alumni.index', 'match' => ['tracer-alumni.index'], 'icon' => 'history', 'admin' => true],
            ],
        ],
        [
            'label' => 'Sistem',
            'items' => [
                ['label' => 'Manajemen user', 'route' => 'users.index', 'match' => ['users.*'], 'icon' => 'shield', 'admin' => true],
                ['label' => 'Pengaturan', 'route' => 'settings.index', 'match' => ['settings.*'], 'icon' => 'sliders', 'admin' => true],
                ['label' => 'Lisensi', 'route' => 'licenses.index', 'match' => ['licenses.*'], 'icon' => 'key', 'admin' => true],
            ],
        ],
    ];

    $isActive = fn (array $patterns): bool => request()->routeIs(...$patterns);
@endphp

<body class="font-sans antialiased bg-[hsl(var(--background))] text-gray-900">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen">
        <!-- Overlay mobile -->
        <div x-show="sidebarOpen" x-transition.opacity.duration.200ms
            class="fixed inset-0 bg-gray-900/30 z-40 lg:hidden" @click="sidebarOpen = false" x-cloak></div>

        <!-- Sidebar -->
        <aside x-cloak
            class="fixed inset-y-0 left-0 z-50 w-64 bg-[#fbfaf8] border-r border-gray-900/[0.08] transition-transform duration-300 ease-[cubic-bezier(0.22,1,0.36,1)] lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
            <div class="flex flex-col h-full">
                <!-- Identitas -->
                <div class="flex items-center gap-3 h-16 px-5 shrink-0">
                    <x-app-logo size="sm" class="shrink-0" />
                    <div class="min-w-0">
                        <p class="text-sm font-semibold leading-tight truncate">
                            {{ \App\Models\SchoolSetting::get('nama_sekolah', 'Data Induk Sekolah') }}
                        </p>
                        <p class="text-xs text-gray-500 leading-tight">Data induk MI &amp; SMP</p>
                    </div>
                    <button type="button" @click="sidebarOpen = false" aria-label="Tutup menu"
                        class="lg:hidden ml-auto p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigasi -->
                <nav class="flex-1 overflow-y-auto px-3 pb-6" aria-label="Menu admin">
                    @foreach ($sections as $section)
                        @php
                            $items = array_values(array_filter($section['items'], fn ($i) => $isAdmin || ! $i['admin']));
                        @endphp
                        @continue(empty($items))

                        @if ($section['label'])
                            <p class="px-2.5 pt-6 pb-2 text-xs text-gray-500">{{ $section['label'] }}</p>
                        @endif

                        <div class="flex flex-col gap-0.5">
                            @foreach ($items as $item)
                                @if (isset($item['children']))
                                    @php
                                        $groupActive = $isActive(array_merge(...array_column($item['children'], 'match')));
                                    @endphp
                                    <div x-data="{ open: {{ $groupActive ? 'true' : 'false' }} }">
                                        <button type="button" @click="open = !open" :aria-expanded="open"
                                            class="flex items-center gap-2.5 w-full h-9 px-2.5 rounded-lg text-sm text-left transition-colors duration-150 {{ $groupActive ? 'text-gray-900 font-medium' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                            <svg class="w-4 h-4 shrink-0 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">{!! $icons[$item['icon']] !!}</svg>
                                            <span class="flex-1 truncate">{{ $item['label'] }}</span>
                                            <svg class="w-3.5 h-3.5 shrink-0 text-gray-400 transition-transform duration-150" :class="open && 'rotate-90'" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="m9 6 6 6-6 6" />
                                            </svg>
                                        </button>
                                        <div x-show="open" x-collapse x-cloak class="flex flex-col gap-0.5 pt-0.5">
                                            @foreach ($item['children'] as $child)
                                                @php $on = $isActive($child['match']); @endphp
                                                <a href="{{ route($child['route']) }}" wire:navigate
                                                    @if ($on) aria-current="page" @endif
                                                    class="flex items-center h-8 pl-[38px] pr-2.5 rounded-lg text-sm transition-colors duration-150 {{ $on ? 'bg-madrasah-100 text-madrasah-700 font-medium' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                                                    <span class="truncate">{{ $child['label'] }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    @php $on = $isActive($item['match']); @endphp
                                    <a href="{{ route($item['route']) }}" wire:navigate
                                        @if ($on) aria-current="page" @endif
                                        class="flex items-center gap-2.5 h-9 px-2.5 rounded-lg text-sm transition-colors duration-150 {{ $on ? 'bg-madrasah-100 text-madrasah-700 font-medium' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        <svg class="w-4 h-4 shrink-0 {{ $on ? 'text-madrasah-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">{!! $icons[$item['icon']] !!}</svg>
                                        <span class="truncate">{{ $item['label'] }}</span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </nav>

                <!-- Pengguna -->
                <div class="shrink-0 border-t border-gray-900/[0.08] p-3">
                    <div class="flex items-center gap-2.5 px-2 py-1.5">
                        <span
                            class="w-8 h-8 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center text-xs font-medium shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </span>
                        <div class="flex-1 min-w-0">
                            @if ($isAdmin)
                                <a href="{{ route('profile') }}" wire:navigate
                                    class="block text-sm font-medium text-gray-900 truncate hover:text-madrasah-700">
                                    {{ auth()->user()->name ?? 'User' }}
                                </a>
                            @else
                                <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                            @endif
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" aria-label="Keluar" title="Keluar"
                                class="p-2 rounded-lg text-gray-500 transition-colors duration-150 hover:bg-gray-100 hover:text-gray-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Konten -->
        <div class="lg:pl-64">
            <header
                class="sticky top-0 z-30 flex items-center gap-3 h-14 px-4 sm:px-8 bg-[hsl(var(--background))]/90 backdrop-blur border-b border-gray-900/[0.08]">
                <button type="button" @click="sidebarOpen = true" aria-label="Buka menu"
                    class="lg:hidden p-2 -ml-2 rounded-lg text-gray-600 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                @isset($header)
                    <h1 class="text-base font-medium text-gray-900 truncate">{{ $header }}</h1>
                @endisset

                <a href="/" target="_blank"
                    class="ml-auto hidden sm:inline-flex items-center gap-2 h-8 px-3 rounded-lg text-sm text-gray-600 transition-colors duration-150 hover:bg-gray-100 hover:text-gray-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M15 3h6v6M10 14 21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                    </svg>
                    Lihat situs
                </a>
            </header>

            <main class="px-4 sm:px-8 py-6 max-w-[1440px]">
                @if (session('success'))
                    <div role="status" class="mb-6 px-4 py-3 rounded-lg bg-madrasah-50 border border-madrasah-200 text-sm text-madrasah-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div role="alert" class="mb-6 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-800">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Modal Portal for x-teleport --}}
    <div id="modal-portal"></div>

    @livewireScripts
</body>

</html>
