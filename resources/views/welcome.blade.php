<x-layouts.app title="Data Induk Sekolah">
    <!-- Navigation -->
    <nav class="nav-apple" x-data="{ scrolled: false, mobileOpen: false }" @scroll.window="scrolled = window.scrollY > 50"
        :class="{ 'scrolled': scrolled }">
        <div class="container-tight">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 font-semibold text-[hsl(var(--foreground))]">
                    <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="hidden sm:inline">Data Induk Sekolah</span>
                </a>

                <!-- Right Side -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm" wire:navigate>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm" wire:navigate>
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero gradient-mesh">
        <div class="hero-content animate-fade-up">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-100 text-sm font-medium text-emerald-800 mb-6">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                </svg>
                Sistem Informasi Sekolah
            </div>

            <h1 class="hero-title text-balance mb-6">
                Data Induk Sekolah<br>MI & SMP
            </h1>

            <p class="text-lg md:text-xl text-[hsl(var(--muted-foreground))] max-w-2xl mx-auto mb-8">
                Sistem manajemen data sekolah terintegrasi untuk mengelola data Siswa, Guru, dan Mata Pelajaran dengan
                pemisahan jenjang MI dan SMP.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg" wire:navigate>
                        Buka Dashboard
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg" wire:navigate>
                        Masuk ke Sistem
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section-padding">
        <div class="container-tight">
            <div class="text-center mb-16 animate-fade-up">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Fitur Utama</h2>
                <p class="text-[hsl(var(--muted-foreground))] max-w-2xl mx-auto">
                    Kelola seluruh data induk sekolah dengan mudah dan terintegrasi.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="card p-6 animate-fade-up delay-100">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Data Siswa</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Kelola data siswa MI dan SMP lengkap dengan NISN, NIK, biodata, dan dokumen pendukung.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="card p-6 animate-fade-up delay-200">
                    <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Data Guru</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Manajemen data guru dengan NIP, NUPTK, NPK, status kepegawaian dan dokumen SK.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="card p-6 animate-fade-up delay-300">
                    <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Mata Pelajaran</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Atur daftar mata pelajaran dengan kelompok PAI dan Umum, dilengkapi drag & drop reorder.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="card p-6 animate-fade-up delay-100">
                    <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Import & Export</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Import dan export data melalui file Excel untuk kemudahan migrasi dan backup data.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="card p-6 animate-fade-up delay-200">
                    <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Cetak Dokumen</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Cetak surat mutasi siswa dan dokumen lainnya langsung dalam format PDF.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="card p-6 animate-fade-up delay-300">
                    <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">API Sync</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Sinkronisasi data dari API eksternal untuk integrasi dengan sistem lain.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-[hsl(var(--border))] py-12">
        <div class="container-tight">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-[hsl(var(--muted-foreground))]">
                    © {{ date('Y') }} Data Induk Sekolah. Dibuat dengan ❤️
                </p>
                <div class="flex items-center gap-6">
                    <span class="text-sm text-[hsl(var(--muted-foreground))]">
                        Laravel + Livewire
                    </span>
                </div>
            </div>
        </div>
    </footer>
</x-layouts.app>
