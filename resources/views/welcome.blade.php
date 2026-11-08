@php
    // Halaman publik sengaja minimal: aplikasi dipakai internal, jadi tidak menampilkan menu,
    // statistik, maupun identitas sekolah selain nama.
    $namaSekolah = \App\Models\SchoolSetting::get('nama_sekolah', 'Data Induk Sekolah');
    $namaYayasan = \App\Models\SchoolSetting::get('nama_yayasan', 'Yayasan Pendidikan');
@endphp
<x-layouts.app :title="$namaSekolah">
    <div class="min-h-screen flex flex-col bg-[#f7f4ee] text-[#1f1b16]">
        <header class="border-b border-[#1f1b16]/[0.08]">
            <div class="max-w-6xl mx-auto px-6 sm:px-8 h-[72px] flex items-center justify-between gap-6">
                <a href="/" class="flex items-center gap-3 min-w-0">
                    <x-app-logo size="sm" />
                    <span class="text-[15px] font-semibold leading-tight truncate">{{ $namaSekolah }}</span>
                </a>
                <div class="flex items-center gap-6">
                <a href="#data-siswa"
                    class="hidden sm:inline text-sm text-[#5e554a] transition-colors duration-150 hover:text-[#1f1b16]">Data Siswa</a>
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="shrink-0 whitespace-nowrap inline-flex items-center h-9 px-4 rounded-lg bg-[#1f1b16] text-[#f7f4ee] text-sm font-medium transition-colors duration-150 hover:bg-[#3a342c] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-madrasah-600">
                        Buka dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="shrink-0 whitespace-nowrap inline-flex items-center h-9 px-4 rounded-lg bg-[#1f1b16] text-[#f7f4ee] text-sm font-medium transition-colors duration-150 hover:bg-[#3a342c] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-madrasah-600">
                        Masuk
                    </a>
                @endauth
                </div>
            </div>
        </header>

        <main class="flex-1 flex items-center">
            <div
                class="max-w-6xl mx-auto w-full px-6 sm:px-8 py-16 lg:py-20 grid lg:grid-cols-2 gap-16 items-center animate-fade-up">
                <div class="flex flex-col gap-6">
                    <p class="text-sm text-[#7b7266]">{{ $namaYayasan }}</p>
                    <h1
                        class="font-serif font-normal text-[40px] sm:text-[56px] leading-[1.05] tracking-[-0.02em] text-[#1f1b16]">
                        Buku induk dan administrasi sekolah, tertata dalam satu portal
                    </h1>
                    <p class="text-lg leading-7 text-[#5e554a] max-w-xl">
                        Data siswa dan guru, surat dinas, SK, serta kuitansi dan LPJ BOS untuk MI dan SMP.
                        Akses khusus tata usaha dan guru.
                    </p>
                    <div class="pt-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-accent btn-accent-lg">
                                Buka dashboard
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-accent btn-accent-lg">
                                Masuk ke portal
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                </svg>
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Momen: contoh surat ber-kop. Isi ilustratif, bukan data siswa sungguhan. --}}
                <div class="relative h-[480px] sm:h-[540px] hidden sm:block" aria-hidden="true">
                    <div class="absolute top-6 left-16 right-0 bottom-2 rounded-md bg-[#efe9df] rotate-[3deg]"></div>
                    <div
                        class="absolute top-0 left-6 right-8 bottom-6 rounded-md bg-[#fffdf8] -rotate-[1.5deg] shadow-[0_1px_2px_rgba(31,27,22,.06),0_16px_40px_rgba(31,27,22,.10)] px-9 py-8 flex flex-col gap-4 font-['Times_New_Roman',Georgia,serif]">
                        <div class="flex items-center gap-4 pb-3 border-b-[3px] border-double border-[#1f1b16]">
                            <div
                                class="w-[52px] h-[52px] rounded-full border-[1.5px] border-madrasah-600 text-madrasah-600 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M2 4h6a4 4 0 0 1 4 4v13a3 3 0 0 0-3-3H2zM22 4h-6a4 4 0 0 0-4 4v13a3 3 0 0 1 3-3h7z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-center flex flex-col gap-0.5">
                                <span class="text-[11px] tracking-[0.04em] uppercase">{{ $namaYayasan }}</span>
                                <span class="text-base font-bold uppercase">{{ $namaSekolah }}</span>
                                <span class="mx-auto mt-1 h-1.5 w-48 rounded bg-[#1f1b16]/10"></span>
                            </div>
                        </div>
                        <div class="text-center flex flex-col gap-1 pt-1">
                            <span class="text-[15px] font-bold underline">SURAT KETERANGAN AKTIF</span>
                            <span class="font-mono text-[11px]">Nomor: 421.2/045/MI/XI/2026</span>
                        </div>
                        <div class="flex flex-col gap-2 pt-1">
                            <span class="h-2 w-full rounded bg-[#1f1b16]/10"></span>
                            <span class="h-2 w-4/5 rounded bg-[#1f1b16]/10"></span>
                        </div>
                        <div class="grid grid-cols-[110px_12px_1fr] gap-y-2 text-[12.5px] pl-4 items-center">
                            <span>Nama</span><span>:</span><span class="h-2 w-40 rounded bg-[#1f1b16]/15"></span>
                            <span>NISN</span><span>:</span><span class="h-2 w-28 rounded bg-[#1f1b16]/15"></span>
                            <span>Kelas</span><span>:</span><span class="h-2 w-16 rounded bg-[#1f1b16]/15"></span>
                            <span>Tahun ajaran</span><span>:</span><span class="h-2 w-24 rounded bg-[#1f1b16]/15"></span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span class="h-2 w-full rounded bg-[#1f1b16]/10"></span>
                            <span class="h-2 w-3/5 rounded bg-[#1f1b16]/10"></span>
                        </div>
                        <div class="mt-auto self-end w-52 relative flex flex-col gap-1 text-[12.5px]">
                            <span>Kepala Madrasah,</span>
                            <div class="h-14"></div>
                            <span class="h-2 w-36 rounded bg-[#1f1b16]/15"></span>
                            <svg class="absolute -left-12 top-0 -rotate-[14deg] opacity-[.78]" width="104"
                                height="104" viewBox="0 0 104 104">
                                <circle cx="52" cy="52" r="49" fill="none" stroke="#3d4f9a" stroke-width="2" />
                                <circle cx="52" cy="52" r="29" fill="none" stroke="#3d4f9a" stroke-width="1.2" />
                                <circle cx="52" cy="52" r="39" fill="none" stroke="#3d4f9a" stroke-width=".8"
                                    stroke-dasharray="2 3" />
                                <text x="52" y="50" text-anchor="middle" font-family="Arial, sans-serif"
                                    font-size="8" font-weight="700" fill="#3d4f9a">KEPALA</text>
                                <text x="52" y="61" text-anchor="middle" font-family="Arial, sans-serif"
                                    font-size="8" font-weight="700" fill="#3d4f9a">MADRASAH</text>
                            </svg>
                        </div>
                    </div>
                    <div
                        class="absolute left-0 bottom-0 bg-[#fdfbf7] rounded-xl px-4 py-3 flex items-center gap-3 shadow-[inset_0_0_0_1px_rgba(31,27,22,.08),0_8px_24px_rgba(31,27,22,.08)]">
                        <span class="w-8 h-8 rounded-lg bg-madrasah-100 text-madrasah-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                <path d="M6 14h12v8H6z" />
                            </svg>
                        </span>
                        <span class="flex flex-col gap-0.5">
                            <span class="text-[13px] font-medium">Nomor surat terisi otomatis</span>
                            <span class="text-xs text-[#7b7266]">PDF ber-kop, siap cetak</span>
                        </span>
                    </div>
                </div>
            </div>
        </main>

        {{-- Validasi data siswa untuk wali kelas (dilindungi PIN) --}}
        <section id="data-siswa" class="py-20 bg-[#efe9df] border-t border-[#1f1b16]/[0.08] scroll-mt-4">
            @livewire('validasi-data-siswa-form')
        </section>

        <footer class="border-t border-[#1f1b16]/[0.08]">
            <div
                class="max-w-6xl mx-auto px-6 sm:px-8 py-6 flex flex-wrap justify-between gap-2 text-[13px] text-[#7b7266]">
                <span>&copy; {{ date('Y') }} {{ $namaYayasan }}</span>
                <span>Khusus penggunaan internal</span>
            </div>
        </footer>
    </div>
</x-layouts.app>
