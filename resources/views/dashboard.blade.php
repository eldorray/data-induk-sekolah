@use('App\Models\GuruMi')
@use('App\Models\GuruSmp')
@use('App\Models\SiswaMi')
@use('App\Models\SiswaSmp')
@use('App\Models\ValidasiDataSiswa')
@php
    $perRombel = fn (string $model) => $model::query()
        ->where('status', 'Aktif')
        ->whereNotNull('tingkat_rombel')
        ->where('tingkat_rombel', '!=', '')
        ->selectRaw('tingkat_rombel, count(*) as jumlah')
        ->groupBy('tingkat_rombel')
        ->orderBy('tingkat_rombel')
        ->pluck('jumlah', 'tingkat_rombel');

    // "Kelas 1 - KELAS 1A" -> "1A"
    $singkat = fn (string $rombel): string => (string) str($rombel)->afterLast('-')->trim()->replaceMatches('/^kelas\s*/i', '');

    $rombelMi = $perRombel(SiswaMi::class);
    $rombelSmp = $perRombel(SiswaSmp::class);

    $siswaMi = SiswaMi::where('status', 'Aktif')->count();
    $siswaSmp = SiswaSmp::where('status', 'Aktif')->count();

    $bars = collect($rombelMi)->map(fn ($n, $k) => ['kelas' => $singkat($k), 'n' => $n, 'mi' => true])->values()
        ->concat(collect($rombelSmp)->map(fn ($n, $k) => ['kelas' => $singkat($k), 'n' => $n, 'mi' => false])->values());
    $barMax = max(1, (int) $bars->max('n'));

    // Status validasi wali kelas per rombel aktif
    $validasi = ValidasiDataSiswa::all()->keyBy(fn ($v) => $v->sekolah . '|' . $v->tingkat_rombel);
    $kelasValidasi = collect(['MI', 'SMP'])->flatMap(fn ($s) => collect(ValidasiDataSiswa::kelasAktif($s))
        ->map(fn ($k) => [
            'label' => $s . ' ' . $singkat($k),
            'status' => $validasi->get($s . '|' . $k)?->status ?? ValidasiDataSiswa::STATUS_BELUM,
        ]));
    $kelasLengkap = $kelasValidasi->where('status', ValidasiDataSiswa::STATUS_LENGKAP)->count();

    $statusStyle = [
        ValidasiDataSiswa::STATUS_LENGKAP => 'bg-madrasah-600',
        ValidasiDataSiswa::STATUS_ADA_CATATAN => 'bg-amber-600',
        ValidasiDataSiswa::STATUS_BELUM => 'bg-gray-300',
    ];

    $aksiCepat = [
        ['label' => 'Siswa MI', 'route' => 'siswa-mi.index'],
        ['label' => 'Siswa SMP', 'route' => 'siswa-smp.index'],
        ['label' => 'Surat keterangan aktif', 'route' => 'surat-aktif.index'],
        ['label' => 'Mutasi siswa', 'route' => 'mutasi.index'],
        ['label' => 'Kuitansi BOS', 'route' => 'kuitansi-bos.index'],
        ['label' => 'Surat universal', 'route' => 'surat-universal.index'],
    ];
@endphp

<x-layouts.admin>
    <x-slot name="header">Dashboard</x-slot>

    <div class="flex flex-col gap-6 animate-fade-up">
        <div class="flex flex-col gap-1">
            <h2 class="text-[28px] leading-tight font-medium text-gray-900">Ringkasan data induk</h2>
            <p class="text-sm text-gray-600">Siswa aktif dan guru di MI dan SMP</p>
        </div>

        {{-- Angka --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
            @foreach ([
                ['Siswa MI', $siswaMi, 'aktif'],
                ['Siswa SMP', $siswaSmp, 'aktif'],
                ['Guru MI', GuruMi::count(), 'terdata'],
                ['Guru SMP', GuruSmp::count(), 'terdata'],
            ] as [$label, $value, $note])
                <div class="bg-white rounded-xl border border-gray-900/[0.08] p-5 flex flex-col gap-3">
                    <span class="text-[13px] text-gray-600">{{ $label }}</span>
                    <span class="text-[32px] leading-none font-medium tabular-nums text-gray-900">{{ number_format($value, 0, ',', '.') }}</span>
                    <span class="text-xs text-gray-500">{{ $note }}</span>
                </div>
            @endforeach
        </div>

        <div class="grid xl:grid-cols-3 gap-4">
            {{-- Grafik siswa per rombel --}}
            <section class="xl:col-span-2 bg-white rounded-xl border border-gray-900/[0.08] p-6 flex flex-col gap-6 min-w-0">
                <div class="flex flex-wrap items-baseline justify-between gap-3">
                    <div class="flex flex-col gap-1">
                        <h3 class="text-base font-medium text-gray-900">Siswa per rombel</h3>
                        <span class="text-[13px] text-gray-600">{{ number_format($siswaMi + $siswaSmp, 0, ',', '.') }} siswa aktif di dua jenjang</span>
                    </div>
                    <div class="flex gap-4 text-xs text-gray-600">
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-sm bg-madrasah-600"></span>MI</span>
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-sm bg-gray-400"></span>SMP</span>
                    </div>
                </div>

                @if ($bars->isEmpty())
                    <p class="py-16 text-center text-sm text-gray-500">Belum ada siswa aktif dengan rombel.</p>
                @else
                    <div class="overflow-x-auto">
                        <div class="flex items-end gap-3 h-64 border-b border-gray-900/[0.16]"
                            style="min-width: {{ $bars->count() * 44 }}px"
                            role="img" aria-label="Grafik jumlah siswa aktif per rombel">
                            @foreach ($bars as $bar)
                                <div class="flex-1 h-full flex flex-col items-center justify-end gap-2 min-w-[32px]">
                                    <span class="text-xs text-gray-600 tabular-nums">{{ $bar['n'] }}</span>
                                    <div class="w-full max-w-[44px] rounded-t {{ $bar['mi'] ? 'bg-madrasah-600' : 'bg-gray-400' }}"
                                        style="height: {{ round($bar['n'] / $barMax * 85, 1) }}%"></div>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex gap-3 pt-2" style="min-width: {{ $bars->count() * 44 }}px">
                            @foreach ($bars as $bar)
                                <span class="flex-1 min-w-[32px] text-center font-mono text-[11px] text-gray-500 truncate">{{ $bar['kelas'] }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </section>

            {{-- Validasi wali kelas --}}
            <section class="bg-white rounded-xl border border-gray-900/[0.08] p-6 flex flex-col gap-4 min-w-0">
                <div class="flex items-baseline justify-between gap-3">
                    <h3 class="text-base font-medium text-gray-900">Validasi wali kelas</h3>
                    <a href="{{ route('validasi-data-siswa.index') }}" wire:navigate class="text-[13px] text-madrasah-700 hover:text-madrasah-800">Lihat</a>
                </div>
                <p class="-mt-2 text-[13px] text-gray-600">
                    {{ $kelasLengkap }} dari {{ $kelasValidasi->count() }} rombel sudah lengkap
                </p>
                <div class="h-1 rounded-full bg-gray-100">
                    <div class="h-1 rounded-full bg-madrasah-600"
                        style="width: {{ $kelasValidasi->count() ? round($kelasLengkap / $kelasValidasi->count() * 100) : 0 }}%"></div>
                </div>
                <ul class="flex flex-col max-h-64 overflow-y-auto -mx-2">
                    @forelse ($kelasValidasi as $kelas)
                        <li class="flex items-center justify-between h-9 px-2 text-sm">
                            <span class="text-gray-900">{{ $kelas['label'] }}</span>
                            <span class="flex items-center gap-2 text-[13px] text-gray-600">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusStyle[$kelas['status']] ?? 'bg-gray-300' }}"></span>
                                {{ ValidasiDataSiswa::STATUS_LABEL[$kelas['status']] ?? $kelas['status'] }}
                            </span>
                        </li>
                    @empty
                        <li class="px-2 py-8 text-center text-sm text-gray-500">Belum ada rombel aktif.</li>
                    @endforelse
                </ul>
            </section>
        </div>

        {{-- Aksi cepat --}}
        <section class="bg-white rounded-xl border border-gray-900/[0.08] overflow-hidden">
            <h3 class="px-6 pt-5 pb-3 text-base font-medium text-gray-900">Buka cepat</h3>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($aksiCepat as $aksi)
                    <a href="{{ route($aksi['route']) }}" wire:navigate
                        class="flex items-center justify-between h-12 px-6 border-t border-gray-900/[0.06] text-sm text-gray-900 transition-colors duration-150 hover:bg-gray-50">
                        {{ $aksi['label'] }}
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="m9 6 6 6-6 6" />
                        </svg>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
</x-layouts.admin>
