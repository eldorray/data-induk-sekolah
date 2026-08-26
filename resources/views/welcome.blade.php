@php
    $namaSekolah = \App\Models\SchoolSetting::get('nama_sekolah', 'Data Induk Sekolah');
    $namaYayasan = \App\Models\SchoolSetting::get('nama_yayasan', 'Yayasan Pendidikan Daarul Hikmah Al Madani');
    $npsn = \App\Models\SchoolSetting::get('npsn', '69755384');
    $nsm = \App\Models\SchoolSetting::get('nsm', '111236710070');
    $telepon = \App\Models\SchoolSetting::get('telepon', '(021) 55722762');
    $email = \App\Models\SchoolSetting::get('email', 'midaarulhikma@gmail.com');
    $alamat = \App\Models\SchoolSetting::get('alamat', 'Karangsari, Neglasari');
    $kota = \App\Models\SchoolSetting::get('kota', 'Kota Tangerang');
    $provinsi = \App\Models\SchoolSetting::get('provinsi', 'Banten');

    $siswaMiCount = \App\Models\SiswaMi::count();
    $siswaSmpCount = \App\Models\SiswaSmp::count();
    $guruMiCount = \App\Models\GuruMi::count();
    $guruSmpCount = \App\Models\GuruSmp::count();
    $totalSiswa = $siswaMiCount + $siswaSmpCount;
    $totalGuru = $guruMiCount + $guruSmpCount;
    $tracerCount = \App\Models\TracerAlumni::count();
@endphp

<x-layouts.app title="{{ $namaSekolah }} - Sistem Informasi & Data Induk Terpadu">
    <!-- Navigation -->
    <nav class="nav-apple" x-data="{ scrolled: false, mobileOpen: false }" @scroll.window="scrolled = window.scrollY > 30"
        :class="{ 'scrolled': scrolled }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">
                <!-- Logo & Brand -->
                <a href="/" class="flex items-center gap-3 group">
                    <div class="transition-transform duration-300 group-hover:scale-105">
                        <x-app-logo size="sm" />
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-gray-900 leading-tight tracking-tight text-sm sm:text-base">
                            {{ $namaSekolah }}
                        </span>
                        <span class="text-[11px] font-medium text-blue-600 hidden sm:inline leading-none">
                            Jenjang MI &amp; SMP Terpadu
                        </span>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                    <a href="#fitur" class="text-gray-600 hover:text-blue-600 transition-colors">Modul &amp; Fitur</a>
                    <a href="{{ route('tracer-alumni.form') }}" class="inline-flex items-center gap-1.5 text-gray-600 hover:text-blue-600 transition-colors">
                        Tracer Alumni
                        <span class="px-2 py-0.5 text-[10px] font-semibold bg-blue-100 text-blue-700 rounded-full">Publik</span>
                    </a>
                    <a href="#keunggulan" class="text-gray-600 hover:text-blue-600 transition-colors">Keunggulan</a>
                    <a href="#alur" class="text-gray-600 hover:text-blue-600 transition-colors">Alur Kerja</a>
                    <a href="#faq" class="text-gray-600 hover:text-blue-600 transition-colors">FAQ</a>
                </div>

                <!-- Right Action / Auth CTA -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-accent btn-accent-sm gap-2" wire:navigate>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-dark-pill flex items-center gap-2" wire:navigate>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span>Masuk Portal</span>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="flex items-center md:hidden">
                    <button type="button" @click="mobileOpen = !mobileOpen"
                        class="p-2 rounded-xl text-gray-600 hover:text-gray-900 hover:bg-gray-100/80 transition-colors focus:outline-none"
                        aria-label="Toggle Menu">
                        <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div x-show="mobileOpen" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden border-b border-gray-200/80 bg-white/95 backdrop-blur-2xl px-6 py-5 shadow-xl">
            <div class="flex flex-col gap-4">
                <a href="#fitur" @click="mobileOpen = false" class="text-sm font-medium text-gray-700 hover:text-blue-600 py-1">
                    Modul &amp; Fitur
                </a>
                <a href="{{ route('tracer-alumni.form') }}" class="flex items-center justify-between text-sm font-medium text-gray-700 hover:text-blue-600 py-1">
                    <span>Tracer Alumni</span>
                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-blue-100 text-blue-700 rounded-full">Form Publik</span>
                </a>
                <a href="#keunggulan" @click="mobileOpen = false" class="text-sm font-medium text-gray-700 hover:text-blue-600 py-1">
                    Keunggulan Sistem
                </a>
                <a href="#alur" @click="mobileOpen = false" class="text-sm font-medium text-gray-700 hover:text-blue-600 py-1">
                    Alur Kerja
                </a>
                <a href="#faq" @click="mobileOpen = false" class="text-sm font-medium text-gray-700 hover:text-blue-600 py-1">
                    Pertanyaan Umum (FAQ)
                </a>
                <div class="pt-3 border-t border-gray-100 flex flex-col gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-accent w-full justify-center" wire:navigate>
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-dark-pill w-full justify-center" wire:navigate>
                            Masuk ke Portal
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="relative pt-28 pb-20 md:pt-36 md:pb-28 overflow-hidden" style="background: #fbfbfd;">
        <!-- Ambient Glow Elements -->
        <div class="glow-blob bg-blue-200/70" style="width: 520px; height: 520px; top: -180px; left: 10%;"></div>
        <div class="glow-blob bg-indigo-200/60" style="width: 480px; height: 480px; top: 120px; right: 5%;"></div>
        <div class="glow-blob bg-sky-100/80" style="width: 380px; height: 380px; bottom: -100px; left: 30%;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Hero Header -->
            <div class="max-w-3xl mx-auto text-center animate-fade-up">
                <!-- Pill Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-200/80 text-xs sm:text-sm font-semibold text-blue-700 mb-6 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                    <span>Platform Tata Kelola Data Pendidikan Terpadu • MI &amp; SMP</span>
                </div>

                <!-- Main Title -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-gray-950 mb-6 text-balance leading-[1.12]">
                    Satu Portal Pintar untuk<br>
                    <span class="text-gradient-blue">Data Induk &amp; Administrasi</span><br>
                    Madrasah &amp; Sekolah
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-lg md:text-xl text-gray-600 mb-8 max-w-2xl mx-auto leading-relaxed text-balance">
                    Kelola data Siswa, Pendidik, Kurikulum PAI &amp; Umum, Otomatisasi Dokumen &amp; SK Resmi, Laporan BOS, hingga Penelusuran Alumni (Tracer Study) dalam satu ekosistem terintegrasi.
                </p>

                <!-- CTA Actions -->
                <div class="flex flex-wrap items-center justify-center gap-3.5 sm:gap-4 mb-10">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-accent btn-accent-lg" wire:navigate>
                            <span>Buka Dashboard</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-accent btn-accent-lg shadow-blue-600/30" wire:navigate>
                            <span>Masuk ke Sistem</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @endauth

                    <a href="{{ route('tracer-alumni.form') }}" class="btn-accent-outline btn-accent-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span>Isi Form Tracer Alumni</span>
                    </a>
                </div>

                <!-- Trust Micro-Chips -->
                <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3 text-xs text-gray-500">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/80 border border-gray-200/60 shadow-xs">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        12+ Format SK &amp; Dokumen Otomatis
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/80 border border-gray-200/60 shadow-xs">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Export &amp; Import Excel (.xlsx)
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/80 border border-gray-200/60 shadow-xs">
                        <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Cetak PDF Siap Print Ber-Kop
                    </span>
                </div>
            </div>

            <!-- Interactive macOS-Style System Mockup Preview -->
            <div class="mt-14 md:mt-18 max-w-5xl mx-auto animate-fade-up delay-100" x-data="{ activeTab: 'stats' }">
                <div class="mac-window">
                    <!-- Window Topbar -->
                    <div class="bg-gray-100/90 border-b border-gray-200/80 px-4 py-3 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-red-400 border border-red-500/30 inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-400 border border-amber-500/30 inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-400 border border-emerald-500/30 inline-block"></span>
                            <span class="text-xs font-mono text-gray-500 ml-2 hidden sm:inline">portal-data-induk.madrasah.id</span>
                        </div>

                        <!-- Tab Switcher -->
                        <div class="flex items-center gap-1 bg-gray-200/70 p-1 rounded-xl text-xs font-medium">
                            <button type="button" @click="activeTab = 'stats'"
                                :class="activeTab === 'stats' ? 'bg-white text-blue-700 shadow-xs font-semibold' : 'text-gray-600 hover:text-gray-900'"
                                class="px-3 py-1 rounded-lg transition-all">
                                Ringkasan Data
                            </button>
                            <button type="button" @click="activeTab = 'surat'"
                                :class="activeTab === 'surat' ? 'bg-white text-blue-700 shadow-xs font-semibold' : 'text-gray-600 hover:text-gray-900'"
                                class="px-3 py-1 rounded-lg transition-all">
                                Generator SK &amp; Surat
                            </button>
                            <button type="button" @click="activeTab = 'tracer'"
                                :class="activeTab === 'tracer' ? 'bg-white text-blue-700 shadow-xs font-semibold' : 'text-gray-600 hover:text-gray-900'"
                                class="px-3 py-1 rounded-lg transition-all">
                                Tracer Alumni
                            </button>
                        </div>
                    </div>

                    <!-- Window Content Body -->
                    <div class="p-6 md:p-8 bg-gradient-to-b from-white to-gray-50/50 min-h-[320px]">
                        <!-- Tab 1: Ringkasan Data -->
                        <div x-show="activeTab === 'stats'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                                <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-blue-800 uppercase tracking-wider">Siswa MI</span>
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                    </div>
                                    <div class="text-2xl sm:text-3xl font-extrabold text-blue-950">{{ $siswaMiCount }}</div>
                                    <p class="text-[11px] text-blue-700 mt-1">NISN &amp; Dokumen Valid</p>
                                </div>
                                <div class="p-4 rounded-xl bg-emerald-50/70 border border-emerald-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-emerald-800 uppercase tracking-wider">Siswa SMP</span>
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    </div>
                                    <div class="text-2xl sm:text-3xl font-extrabold text-emerald-950">{{ $siswaSmpCount }}</div>
                                    <p class="text-[11px] text-emerald-700 mt-1">Terdata Aktif</p>
                                </div>
                                <div class="p-4 rounded-xl bg-purple-50/70 border border-purple-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-purple-800 uppercase tracking-wider">Guru MI</span>
                                        <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                                    </div>
                                    <div class="text-2xl sm:text-3xl font-extrabold text-purple-950">{{ $guruMiCount }}</div>
                                    <p class="text-[11px] text-purple-700 mt-1">GTY &amp; GTT Terverifikasi</p>
                                </div>
                                <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-100">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-amber-800 uppercase tracking-wider">Total Guru</span>
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    </div>
                                    <div class="text-2xl sm:text-3xl font-extrabold text-amber-950">{{ $totalGuru }}</div>
                                    <p class="text-[11px] text-amber-700 mt-1">Pendidik &amp; Staf</p>
                                </div>
                            </div>

                            <!-- Mock mini list inside window -->
                            <div class="rounded-xl border border-gray-200/80 bg-white overflow-hidden shadow-xs">
                                <div class="px-4 py-2.5 bg-gray-50/80 border-b border-gray-200/80 flex items-center justify-between text-xs font-medium text-gray-500">
                                    <span>Modul Aktif Terintegrasi</span>
                                    <span class="text-blue-600 font-semibold">Sinkronisasi Real-time</span>
                                </div>
                                <div class="divide-y divide-gray-100 text-xs">
                                    <div class="p-3 flex items-center justify-between hover:bg-gray-50/50">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-md bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-[10px]">MI</span>
                                            <span class="font-medium text-gray-800">Master Data Siswa &amp; Rekap Mutasi Siswa MI</span>
                                        </div>
                                        <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-medium text-[10px]">Terhubung</span>
                                    </div>
                                    <div class="p-3 flex items-center justify-between hover:bg-gray-50/50">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-md bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-[10px]">SMP</span>
                                            <span class="font-medium text-gray-800">Master Data Siswa &amp; Kurikulum SMP</span>
                                        </div>
                                        <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-medium text-[10px]">Terhubung</span>
                                    </div>
                                    <div class="p-3 flex items-center justify-between hover:bg-gray-50/50">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-md bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-[10px]">SK</span>
                                            <span class="font-medium text-gray-800">Penerbitan SK Pembagian Tugas &amp; SK GTY</span>
                                        </div>
                                        <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-medium text-[10px]">Format Resmi</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 2: Generator SK & Dokumen -->
                        <div x-show="activeTab === 'surat'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div class="p-4 rounded-xl border border-gray-200 bg-white hover:border-blue-300 transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </div>
                                            <h4 class="font-bold text-gray-900 text-sm">Surat Keterangan Aktif</h4>
                                        </div>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 bg-blue-50 text-blue-700 rounded-full">PDF Instan</span>
                                    </div>
                                    <p class="text-xs text-gray-500">Penomoran otomatis dengan kode surat resmi, format kop madrasah, dan tanda tangan digital.</p>
                                </div>

                                <div class="p-4 rounded-xl border border-gray-200 bg-white hover:border-blue-300 transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                            <h4 class="font-bold text-gray-900 text-sm">SK Pembagian Tugas &amp; GTY</h4>
                                        </div>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-full">Lampiran Tabel</span>
                                    </div>
                                    <p class="text-xs text-gray-500">Generate SK Tugas Mengajar lengkap dengan lampiran pembagian jam pelajaran &amp; tugas tambahan.</p>
                                </div>

                                <div class="p-4 rounded-xl border border-gray-200 bg-white hover:border-blue-300 transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            </div>
                                            <h4 class="font-bold text-gray-900 text-sm">Kuitansi &amp; LPJ BOS</h4>
                                        </div>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 bg-amber-50 text-amber-700 rounded-full">Akuntansi BOS</span>
                                    </div>
                                    <p class="text-xs text-gray-500">Format bukti kas kuitansi pengeluaran BOS, rekapitulasi realisasi, dan kompilasi lampiran LPJ.</p>
                                </div>

                                <div class="p-4 rounded-xl border border-gray-200 bg-white hover:border-blue-300 transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <h4 class="font-bold text-gray-900 text-sm">Surat Universal Kustom</h4>
                                        </div>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full">Fleksibel</span>
                                    </div>
                                    <p class="text-xs text-gray-500">Buat surat dinas, rekomendasi, atau keterangan khusus dengan editor format dinamis.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 3: Tracer Alumni -->
                        <div x-show="activeTab === 'tracer'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white mb-4">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div>
                                        <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-[11px] font-semibold mb-2">
                                            Portal Penelusuran Lulusan
                                        </span>
                                        <h4 class="text-xl font-bold">Tracer Study Alumni MI &amp; SMP</h4>
                                        <p class="text-xs sm:text-sm text-blue-100 mt-1 max-w-lg">
                                            Memetakan persebaran kelanjutan studi ke jenjang SMA/SMK/MA/Pondok Pesantren dan karir alumni untuk evaluasi mutu madrasah.
                                        </p>
                                    </div>
                                    <a href="{{ route('tracer-alumni.form') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full bg-white text-blue-700 font-bold text-xs hover:bg-blue-50 transition-colors shrink-0 shadow-md">
                                        <span>Buka Form Tracer</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div class="p-3 bg-white rounded-xl border border-gray-200/80">
                                    <div class="text-lg font-bold text-gray-900">100%</div>
                                    <div class="text-[11px] text-gray-500">Akses Terbuka Tanpa Login</div>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-gray-200/80">
                                    <div class="text-lg font-bold text-blue-600">MI &amp; SMP</div>
                                    <div class="text-[11px] text-gray-500">Lintas Jenjang Pendidikan</div>
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-gray-200/80">
                                    <div class="text-lg font-bold text-emerald-600">Real-time</div>
                                    <div class="text-[11px] text-gray-500">Rekapitulasi Akreditasi</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Metrics Strip -->
    <section class="py-10 bg-white border-y border-gray-200/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                <!-- KPI 1 -->
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-gray-900 tracking-tight">2 Jenjang</div>
                        <div class="text-xs text-gray-500 font-medium">MI &amp; SMP Terpisah Rapi</div>
                    </div>
                </div>

                <!-- KPI 2 -->
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-gray-900 tracking-tight">{{ $totalSiswa > 0 ? $totalSiswa . '+' : '500+' }} Siswa</div>
                        <div class="text-xs text-gray-500 font-medium">Database NISN &amp; NIK</div>
                    </div>
                </div>

                <!-- KPI 3 -->
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-gray-900 tracking-tight">{{ $totalGuru > 0 ? $totalGuru : '24+' }} Pendidik</div>
                        <div class="text-xs text-gray-500 font-medium">Data Guru &amp; SK Tugas</div>
                    </div>
                </div>

                <!-- KPI 4 -->
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-gray-900 tracking-tight">12+ Format</div>
                        <div class="text-xs text-gray-500 font-medium">Dokumen, SK &amp; LPJ BOS</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Features Bento Grid -->
    <section id="fitur" class="py-24 bg-gray-50/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 animate-fade-up">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-xs font-semibold text-blue-800 mb-3">
                    Modul Terpadu
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-950 tracking-tight mb-4">
                    Fitur Lengkap untuk Segala Kebutuhan Sekolah
                </h2>
                <p class="text-base sm:text-lg text-gray-600">
                    Dirancang khusus untuk mendukung operasional tata usaha, pendidik, pimpinan madrasah, dan penelusuran lulusan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Feature 1: Master Data Siswa & Mutasi -->
                <div class="bento-card group">
                    <div class="icon-badge">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-blue-100 text-blue-800">MI &amp; SMP</span>
                        <span class="text-xs text-gray-400 font-mono">Modul Siswa</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Master Data Siswa &amp; Mutasi</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">
                        Pencatatan lengkap biodata siswa, NISN, NIK, orang tua/wali, status aktif, serta manajemen mutasi masuk &amp; keluar dengan cetak surat pindah.
                    </p>
                    <div class="pt-4 border-t border-gray-100 flex flex-wrap gap-1.5">
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Filter Kelas</span>
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Surat Mutasi PDF</span>
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Syarat Pindah</span>
                    </div>
                </div>

                <!-- Feature 2: Data Guru & SK Resmi -->
                <div class="bento-card group">
                    <div class="icon-badge bg-gradient-to-br from-indigo-600 to-indigo-400 shadow-indigo-600/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-indigo-100 text-indigo-800">Kepegawaian</span>
                        <span class="text-xs text-gray-400 font-mono">Pendidik &amp; Staf</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Data Guru &amp; Generator SK</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">
                        Kelola data guru (NIP, NUPTK, NPK), status kepegawaian, serta penerbitan otomatis SK GTY, SK Pembagian Jam Mengajar, dan SK Tugas Tambahan.
                    </p>
                    <div class="pt-4 border-t border-gray-100 flex flex-wrap gap-1.5">
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">SK GTY Yayasan</span>
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Jadwal Mengajar</span>
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Surat Insentif</span>
                    </div>
                </div>

                <!-- Feature 3: Nilai Ijazah Kelas 6 -->
                <div class="bento-card group">
                    <div class="icon-badge bg-gradient-to-br from-emerald-600 to-teal-400 shadow-emerald-600/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-emerald-100 text-emerald-800">Akademik</span>
                        <span class="text-xs text-gray-400 font-mono">Kelulusan</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Nilai Ijazah &amp; Transkrip</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">
                        Modul khusus penginputan nilai raport dan ujian kelas akhir. Dilengkapi cetak Cover Ijazah, Transkrip Nilai Siswa, dan Rekap Nilai Kolektif.
                    </p>
                    <div class="pt-4 border-t border-gray-100 flex flex-wrap gap-1.5">
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Cetak Cover</span>
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Transkrip Nilai</span>
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Ekspor Rekap Excel</span>
                    </div>
                </div>

                <!-- Feature 4: Administrasi Persuratan & Universal -->
                <div class="bento-card group">
                    <div class="icon-badge bg-gradient-to-br from-purple-600 to-purple-400 shadow-purple-600/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-purple-100 text-purple-800">Persuratan</span>
                        <span class="text-xs text-gray-400 font-mono">Dinas Resmi</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Administrasi Surat Otomatis</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">
                        Penerbitan Surat Keterangan Aktif, Surat Rekap PKH, Surat Terima Pindahan, serta generator Surat Universal kustom multi-keperluan.
                    </p>
                    <div class="pt-4 border-t border-gray-100 flex flex-wrap gap-1.5">
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Surat Aktif</span>
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Rekap PKH</span>
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Surat Universal</span>
                    </div>
                </div>

                <!-- Feature 5: Kuitansi & LPJ BOS -->
                <div class="bento-card group">
                    <div class="icon-badge bg-gradient-to-br from-amber-600 to-amber-400 shadow-amber-600/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-amber-100 text-amber-800">Keuangan BOS</span>
                        <span class="text-xs text-gray-400 font-mono">Akuntabilitas</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Kuitansi &amp; LPJ BOS</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">
                        Pembuatan kuitansi bukti kas pengeluaran BOS sesuai standar juknis, format penomoran teratur, dan penyusunan berkas LPJ BOS per tahap.
                    </p>
                    <div class="pt-4 border-t border-gray-100 flex flex-wrap gap-1.5">
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Cetak Kuitansi Massal</span>
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Rekap LPJ Tahap</span>
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Lampiran Bukti</span>
                    </div>
                </div>

                <!-- Feature 6: Import & Export Excel/PDF -->
                <div class="bento-card group">
                    <div class="icon-badge bg-gradient-to-br from-rose-600 to-rose-400 shadow-rose-600/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-rose-100 text-rose-800">Interoperabilitas</span>
                        <span class="text-xs text-gray-400 font-mono">Backup &amp; Cetak</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Impor-Ekspor &amp; PDF Instan</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">
                        Dukungan penuh migrasi data melalui spreadsheet Excel (.xlsx) dan pencetakan dokumen PDF siap print dengan kop madrasah/sekolah resmi.
                    </p>
                    <div class="pt-4 border-t border-gray-100 flex flex-wrap gap-1.5">
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Excel Import/Export</span>
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Kop Surat Resmi</span>
                        <span class="text-[11px] px-2 py-0.5 rounded-md bg-gray-100 text-gray-600">Cetak Satuan &amp; Massal</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dedicated Tracer Alumni Highlight Section -->
    <section id="alumni" class="py-20 md:py-28 bg-white relative overflow-hidden">
        <!-- Glow blob -->
        <div class="glow-blob bg-blue-200/50" style="width: 400px; height: 400px; top: 10%; right: -100px;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="callout-card p-8 md:p-14 lg:p-16">
                <div class="grid lg:grid-cols-12 gap-10 items-center">
                    <!-- Left Column -->
                    <div class="lg:col-span-7">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-100 text-xs font-semibold text-blue-800 mb-6">
                            🎓 Portal Penelusuran Lulusan
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-950 tracking-tight mb-4 text-balance">
                            Tracer Study Alumni MI &amp; SMP
                        </h2>
                        <p class="text-gray-600 text-base sm:text-lg mb-8 leading-relaxed">
                            Bagi alumni MI &amp; SMP, bantu kami mengetahui perjalanan studi lanjut atau karir Anda setelah lulus. Informasi ini digunakan untuk evaluasi kurikulum dan akreditasi almamater.
                        </p>

                        <!-- 3-Step Flow -->
                        <div class="grid sm:grid-cols-3 gap-4 mb-8">
                            <div class="p-4 rounded-xl bg-white/90 border border-blue-100 shadow-xs">
                                <div class="w-7 h-7 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center mb-2">1</div>
                                <div class="font-bold text-gray-900 text-xs mb-1">Isi Data Diri</div>
                                <div class="text-[11px] text-gray-500">Nama, tahun lulus, &amp; kontak terkini.</div>
                            </div>
                            <div class="p-4 rounded-xl bg-white/90 border border-blue-100 shadow-xs">
                                <div class="w-7 h-7 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center mb-2">2</div>
                                <div class="font-bold text-gray-900 text-xs mb-1">Jejak Studi / Karir</div>
                                <div class="text-[11px] text-gray-500">SMA/SMK/MA/Pesantren / Pekerjaan.</div>
                            </div>
                            <div class="p-4 rounded-xl bg-white/90 border border-blue-100 shadow-xs">
                                <div class="w-7 h-7 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center mb-2">3</div>
                                <div class="font-bold text-gray-900 text-xs mb-1">Kirim Langsung</div>
                                <div class="text-[11px] text-gray-500">Tanpa login, cepat &amp; aman.</div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-4">
                            <a href="{{ route('tracer-alumni.form') }}" class="btn-accent btn-accent-lg shadow-blue-600/30">
                                <span>Isi Form Tracer Sekarang</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                            <span class="text-xs text-gray-500">
                                ⚡ Pengisian hanya butuh ~2 menit
                            </span>
                        </div>
                    </div>

                    <!-- Right Column: Visual Card -->
                    <div class="lg:col-span-5">
                        <div class="p-6 md:p-8 rounded-2xl bg-white border border-blue-200/80 shadow-xl shadow-blue-900/5">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-md shadow-blue-600/30">
                                    <x-app-logo size="sm" :showFallback="true" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $namaSekolah }}</h4>
                                    <p class="text-xs text-blue-600 font-medium">Jejak Langkah Alumni</p>
                                </div>
                            </div>

                            <div class="space-y-3.5 text-xs">
                                <div class="p-3 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-between">
                                    <span class="font-medium text-gray-700">Tingkat Lanjut Studi</span>
                                    <span class="font-bold text-blue-700">SMA / SMK / MA / Pesantren</span>
                                </div>
                                <div class="p-3 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-between">
                                    <span class="font-medium text-gray-700">Sumbang Saran Almamater</span>
                                    <span class="font-bold text-emerald-700">Kuesioner Terbuka</span>
                                </div>
                                <div class="p-3 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-between">
                                    <span class="font-medium text-gray-700">Status Validasi</span>
                                    <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-semibold">Terekam Otomatis</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Workflow / Alur Kerja Section -->
    <section id="alur" class="py-24 bg-gray-50/70 border-t border-gray-200/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 animate-fade-up">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-xs font-semibold text-blue-800 mb-3">
                    Alur Kerja Efisien
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-950 tracking-tight mb-4">
                    Tiga Langkah Pengelolaan Administrasi
                </h2>
                <p class="text-base sm:text-lg text-gray-600">
                    Proses tata kelola yang rapi, otomatis, dan minim kesalahan birokrasi sekolah.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 relative">
                <!-- Step 1 -->
                <div class="p-8 rounded-2xl bg-white border border-gray-200/80 shadow-xs relative">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 font-black text-xl flex items-center justify-center mb-6 border border-blue-200/60">
                        01
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Input &amp; Sinkronisasi Data</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Masukkan data siswa, guru, dan mata pelajaran via form terpadu atau impor massal melalui template spreadsheet Excel.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="p-8 rounded-2xl bg-white border border-gray-200/80 shadow-xs relative">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 font-black text-xl flex items-center justify-center mb-6 border border-indigo-200/60">
                        02
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Otomatisasi &amp; Validasi Format</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Sistem menyusun penomoran surat otomatis, memetakan kelompok mapel, menyusun lampiran SK, dan mengompilasi bukti LPJ BOS.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="p-8 rounded-2xl bg-white border border-gray-200/80 shadow-xs relative">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 font-black text-xl flex items-center justify-center mb-6 border border-emerald-200/60">
                        03
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Cetak PDF &amp; Rekapitulasi</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Unduh atau cetak dokumen resmi ber-kop sekolah dalam format PDF berkualitas tinggi dan ekspor data kapan pun dibutuhkan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Keunggulan Section -->
    <section id="keunggulan" class="py-24 bg-white border-t border-gray-200/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-5">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-xs font-semibold text-blue-800 mb-3">
                        Mengapa Memilih Portal Ini?
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-950 tracking-tight mb-6 leading-tight">
                        Dirancang untuk Kebutuhan Riil Madrasah &amp; Sekolah
                    </h2>
                    <p class="text-gray-600 leading-relaxed mb-8">
                        Bukan sekadar buku induk digital biasa, sistem ini mengintegrasikan administrasi akademik, kepegawaian yayasan, persuratan dinas, hingga pertanggungjawaban dana BOS secara holistik.
                    </p>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Pemisahan Jenjang MI &amp; SMP yang Rapi</h4>
                                <p class="text-xs text-gray-500">Masing-masing jenjang memiliki data siswa, guru, dan kurikulum tersendiri namun dapat dipantau dalam satu dashboard.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Standar Kop Surat &amp; Tanda Tangan Resmi</h4>
                                <p class="text-xs text-gray-500">Template dokumen secara otomatis menyematkan kop madrasah/sekolah, stempel, dan nama penandatangan yang tersimpan.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Hemat Waktu &amp; Bebas Duplikasi</h4>
                                <p class="text-xs text-gray-500">Mengeliminasi pencatatan manual berulang, mempercepat pembuatan kuitansi BOS dan surat mutasi siswa.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="p-6 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50/50 border border-blue-100">
                            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-sm mb-4">
                                📊
                            </div>
                            <h4 class="font-bold text-gray-900 mb-2">Rekap Nilai Ijazah</h4>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Form pengisian nilai raport kelas 6 MI dengan perhitungan otomatis, cetak cover, transkrip, dan rekap kolektif siap arsip.
                            </p>
                        </div>

                        <div class="p-6 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50/50 border border-emerald-100">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-sm mb-4">
                                📝
                            </div>
                            <h4 class="font-bold text-gray-900 mb-2">Penomoran Surat Rapi</h4>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Format kode surat otomatis sesuai standar madrasah (`.../MIDH/{{ date('Y') }}`) untuk kuitansi, SK, dan surat keterangan.
                            </p>
                        </div>

                        <div class="p-6 rounded-2xl bg-gradient-to-br from-purple-50 to-pink-50/50 border border-purple-100">
                            <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold text-sm mb-4">
                                🔒
                            </div>
                            <h4 class="font-bold text-gray-900 mb-2">Hak Akses Berbasis Peran</h4>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Proteksi multi-role: Administrator memiliki akses penuh dan Guru dapat mengakses modul nilai ijazah secara aman.
                            </p>
                        </div>

                        <div class="p-6 rounded-2xl bg-gradient-to-br from-amber-50 to-orange-50/50 border border-amber-100">
                            <div class="w-10 h-10 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold text-sm mb-4">
                                ⚡
                            </div>
                            <h4 class="font-bold text-gray-900 mb-2">Teknologi Modern &amp; Cepat</h4>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Dibangun dengan Laravel &amp; Livewire untuk navigasi cepat tanpa reload halaman (*SPA-like experience*).
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-24 bg-gray-50/70 border-t border-gray-200/70" x-data="{ openFaq: 1 }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-up">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-xs font-semibold text-blue-800 mb-3">
                    Bantuan &amp; Panduan
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-950 tracking-tight mb-4">
                    Pertanyaan yang Sering Diajukan
                </h2>
                <p class="text-base text-gray-600">
                    Informasi penting seputar akses sistem, data, dan modul tracer alumni.
                </p>
            </div>

            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="rounded-2xl border border-gray-200/80 bg-white overflow-hidden shadow-xs transition-all">
                    <button type="button" @click="openFaq = openFaq === 1 ? null : 1"
                        class="w-full px-6 py-5 text-left font-bold text-gray-900 flex items-center justify-between gap-4">
                        <span class="text-base sm:text-lg">Siapa saja yang dapat mengakses sistem Data Induk ini?</span>
                        <svg class="w-5 h-5 text-blue-600 transition-transform duration-200 shrink-0" :class="{ 'rotate-180': openFaq === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 1" x-collapse class="px-6 pb-5 text-sm text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                        Sistem ini dapat diakses oleh Administrator sekolah/tata usaha serta Bapak/Ibu Guru melalui akun login masing-masing. Khusus untuk formulir <strong>Tracer Alumni</strong>, halaman dapat diakses secara publik dan terbuka tanpa memerlukan login.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="rounded-2xl border border-gray-200/80 bg-white overflow-hidden shadow-xs transition-all">
                    <button type="button" @click="openFaq = openFaq === 2 ? null : 2"
                        class="w-full px-6 py-5 text-left font-bold text-gray-900 flex items-center justify-between gap-4">
                        <span class="text-base sm:text-lg">Apakah data siswa dan guru MI dan SMP terpisah?</span>
                        <svg class="w-5 h-5 text-blue-600 transition-transform duration-200 shrink-0" :class="{ 'rotate-180': openFaq === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 2" x-collapse class="px-6 pb-5 text-sm text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                        Ya, sistem memiliki modul terpisah antara jenjang MI (Madrasah Ibtidaiyah) dan jenjang SMP untuk menjamin akurasi data NISN, NIK, kurikulum mapel, dan pembagian tugas mengajar guru sesuai tingkatan pendidikannya.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="rounded-2xl border border-gray-200/80 bg-white overflow-hidden shadow-xs transition-all">
                    <button type="button" @click="openFaq = openFaq === 3 ? null : 3"
                        class="w-full px-6 py-5 text-left font-bold text-gray-900 flex items-center justify-between gap-4">
                        <span class="text-base sm:text-lg">Bagaimana cara alumni mengisi survei Tracer Alumni?</span>
                        <svg class="w-5 h-5 text-blue-600 transition-transform duration-200 shrink-0" :class="{ 'rotate-180': openFaq === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 3" x-collapse class="px-6 pb-5 text-sm text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                        Alumni cukup mengklik tombol <a href="{{ route('tracer-alumni.form') }}" class="text-blue-600 font-semibold hover:underline">Form Tracer Alumni</a> di halaman ini, memilih jenjang kelulusan (MI atau SMP), mengisi tahun kelulusan, kontak, serta jejak studi lanjut / pekerjaan saat ini.
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="rounded-2xl border border-gray-200/80 bg-white overflow-hidden shadow-xs transition-all">
                    <button type="button" @click="openFaq = openFaq === 4 ? null : 4"
                        class="w-full px-6 py-5 text-left font-bold text-gray-900 flex items-center justify-between gap-4">
                        <span class="text-base sm:text-lg">Bagaimana cara mencetak surat keterangan dan SK resmi?</span>
                        <svg class="w-5 h-5 text-blue-600 transition-transform duration-200 shrink-0" :class="{ 'rotate-180': openFaq === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 4" x-collapse class="px-6 pb-5 text-sm text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                        Setelah masuk ke Dashboard Admin, pilih menu surat yang diinginkan (misalnya Surat Aktif, Surat Mutasi, SK Pembagian Tugas, atau Kuitansi BOS). Klik tombol <strong>Cetak PDF</strong> untuk mengunduh dokumen dengan tata letak siap print lengkap ber-kop madrasah.
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="rounded-2xl border border-gray-200/80 bg-white overflow-hidden shadow-xs transition-all">
                    <button type="button" @click="openFaq = openFaq === 5 ? null : 5"
                        class="w-full px-6 py-5 text-left font-bold text-gray-900 flex items-center justify-between gap-4">
                        <span class="text-base sm:text-lg">Apakah data dapat diekspor ke Excel untuk backup?</span>
                        <svg class="w-5 h-5 text-blue-600 transition-transform duration-200 shrink-0" :class="{ 'rotate-180': openFaq === 5 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 5" x-collapse class="px-6 pb-5 text-sm text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                        Tentu saja. Tersedia fitur ekspor ke format Excel (.xlsx) untuk seluruh data siswa, data guru, rekap nilai ijazah, dan data tracer alumni sehingga sekolah memiliki salinan backup data yang aman dan fleksibel.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom Call-to-Action Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-700 text-white p-8 sm:p-12 md:p-16 shadow-2xl shadow-blue-700/20 text-center">
                <!-- Decorative Circles -->
                <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -right-24 w-72 h-72 rounded-full bg-indigo-400/20 blur-2xl pointer-events-none"></div>

                <div class="relative z-10 max-w-3xl mx-auto">
                    <span class="inline-block px-4 py-1 rounded-full bg-white/15 text-xs font-semibold tracking-wide mb-4">
                        Sistem Informasi &amp; Administrasi Terpadu
                    </span>
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight mb-6 text-balance">
                        Wujudkan Tata Kelola Administrasi Madrasah yang Modern &amp; Akuntabel
                    </h2>
                    <p class="text-base sm:text-lg text-blue-100 mb-8 max-w-xl mx-auto leading-relaxed">
                        Masuk ke portal administrasi untuk mulai mengelola data atau isi form tracer alumni untuk mendukung almamater tercinta.
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 px-8 h-12 rounded-full bg-white text-blue-700 font-bold text-sm hover:bg-blue-50 transition-all hover:scale-105 shadow-lg shadow-black/10" wire:navigate>
                                <span>Buka Dashboard</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-8 h-12 rounded-full bg-white text-blue-700 font-bold text-sm hover:bg-blue-50 transition-all hover:scale-105 shadow-lg shadow-black/10" wire:navigate>
                                <span>Masuk ke Portal</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endauth
                        <a href="{{ route('tracer-alumni.form') }}" class="inline-flex items-center justify-center gap-2 px-8 h-12 rounded-full bg-blue-800/60 border border-white/30 text-white font-semibold text-sm hover:bg-blue-800/80 transition-all">
                            <span>Form Tracer Alumni</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Comprehensive Modern Footer -->
    <footer class="bg-gray-950 text-gray-400 pt-16 pb-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-gray-800/80">
                <!-- Col 1 & 2: School Brand & Identity -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="p-1.5 rounded-xl bg-white/10">
                            <x-app-logo size="sm" :showFallback="true" />
                        </div>
                        <div>
                            <span class="font-bold text-white text-base block">{{ $namaSekolah }}</span>
                            @if($namaYayasan)
                                <span class="text-xs text-gray-400 block">{{ $namaYayasan }}</span>
                            @endif
                        </div>
                    </div>

                    <p class="text-xs sm:text-sm text-gray-400 leading-relaxed max-w-sm">
                        Sistem manajemen data induk, administrasi akademik, kepegawaian, otomatisasi persuratan, dan penelusuran alumni terpadu jenjang MI &amp; SMP.
                    </p>

                    <div class="pt-2 text-xs space-y-1.5 text-gray-400">
                        @if($npsn || $nsm)
                            <div class="flex flex-wrap gap-2 text-[11px]">
                                @if($npsn)
                                    <span class="px-2 py-0.5 rounded bg-gray-800 text-gray-300 font-mono">NPSN: {{ $npsn }}</span>
                                @endif
                                @if($nsm)
                                    <span class="px-2 py-0.5 rounded bg-gray-800 text-gray-300 font-mono">NSM: {{ $nsm }}</span>
                                @endif
                            </div>
                        @endif
                        @if($alamat)
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ $alamat }}{{ $kota ? ', ' . $kota : '' }}{{ $provinsi ? ', ' . $provinsi : '' }}</span>
                            </div>
                        @endif
                        @if($telepon || $email)
                            <div class="flex flex-wrap items-center gap-3 pt-1">
                                @if($telepon)
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        {{ $telepon }}
                                    </span>
                                @endif
                                @if($email)
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        {{ $email }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Col 3: Navigasi Cepat -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Navigasi</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#hero" class="hover:text-white transition-colors">Beranda Utama</a></li>
                        <li><a href="#fitur" class="hover:text-white transition-colors">Modul &amp; Fitur</a></li>
                        <li><a href="#keunggulan" class="hover:text-white transition-colors">Keunggulan Sistem</a></li>
                        <li><a href="#alur" class="hover:text-white transition-colors">Alur Pengelolaan</a></li>
                        <li><a href="#faq" class="hover:text-white transition-colors">Pertanyaan Umum</a></li>
                    </ul>
                </div>

                <!-- Col 4: Modul Utama -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Modul Terintegrasi</h4>
                    <ul class="space-y-2 text-xs">
                        <li><span class="text-gray-400">Master Data Siswa MI &amp; SMP</span></li>
                        <li><span class="text-gray-400">Data Guru &amp; SK Mengajar</span></li>
                        <li><span class="text-gray-400">Nilai Ijazah Kelas 6 MI</span></li>
                        <li><span class="text-gray-400">Generator Surat &amp; Mutasi</span></li>
                        <li><span class="text-gray-400">Kuitansi &amp; LPJ BOS</span></li>
                    </ul>
                </div>

                <!-- Col 5: Layanan Publik & Auth -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Layanan Publik</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('tracer-alumni.form') }}" class="text-blue-400 hover:text-blue-300 font-semibold">Form Tracer Alumni</a></li>
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="hover:text-white transition-colors" wire:navigate>Buka Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors" wire:navigate>Masuk ke Akun</a></li>
                        @endauth
                    </ul>
                </div>
            </div>

            <!-- Bottom Copyright Bar -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500">
                <div>
                    © {{ date('Y') }} {{ $namaSekolah }}. Seluruh hak cipta dilindungi.
                </div>
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Laravel &amp; Livewire
                    </span>
                    <span>•</span>
                    <span>Modern SaaS Architecture</span>
                </div>
            </div>
        </div>
    </footer>
</x-layouts.app>
