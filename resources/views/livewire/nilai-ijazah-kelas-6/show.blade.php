<div>
    {{-- Header: breadcrumb + info tahun ajaran --}}
    <div class="mb-6">
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-3">
            <a href="{{ route('nilai-ijazah.index') }}" wire:navigate class="hover:text-gray-900">Nilai Ijazah Kelas 6</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-900 font-medium">{{ $tahunAjaran->nama_tahun_ajaran }}</span>
        </nav>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Tahun Ajaran {{ $tahunAjaran->nama_tahun_ajaran }}</h2>
                <p class="text-sm text-gray-500">
                    Status:
                    @if ($tahunAjaran->status)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">Nonaktif</span>
                    @endif
                    @if ($tahunAjaran->keterangan)
                        &middot; {{ $tahunAjaran->keterangan }}
                    @endif
                </p>
            </div>
            <a href="{{ route('nilai-ijazah.index') }}" wire:navigate
                class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tabs utama --}}
    <div class="flex items-center gap-1 border-b border-gray-200 mb-6 overflow-x-auto">
        @php
            $tabs = [
                'raport' => 'Input Nilai Rata-rata Raport',
                'um' => 'Input Nilai UM',
                'rekap' => 'Nilai Rata-rata',
                'cetak' => 'Cetak',
            ];
        @endphp
        @foreach ($tabs as $key => $label)
            <button type="button" wire:click="$set('tab', '{{ $key }}')"
                class="px-4 py-3 text-sm font-medium border-b-2 -mb-px transition-colors
                    {{ $tab === $key ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- ============================ TAB: RAPORT (per semester) ============================ --}}
    @if ($tab === 'raport')
        {{-- Sub-tab semester --}}
        <div class="flex flex-wrap items-center gap-2 mb-4">
            @foreach ($semesterOptions as $key => $label)
                <button type="button" wire:click="selectSemester('{{ $key }}')"
                    class="px-4 py-2 rounded-xl text-sm font-medium border transition-colors
                        {{ $semesterTab === $key
                            ? 'bg-gray-900 text-white border-gray-900'
                            : 'bg-white text-gray-700 border-gray-200 hover:border-gray-300 hover:bg-gray-50' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Toolbar search --}}
        <div class="flex flex-col sm:flex-row justify-between gap-3 mb-4">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="searchSiswa" placeholder="Cari nama/NISN siswa..."
                    class="pl-10 pr-4 py-2 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent w-72 text-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <div class="text-sm text-gray-500 self-center">
                {{ $siswas->count() }} siswa &middot; {{ $mapels->count() }} mapel
                &middot; Sedang input: <strong class="text-gray-900">{{ $semesterOptions[$semesterTab] ?? '-' }}</strong>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase sticky left-0 bg-gray-50 z-10">No</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase sticky left-10 bg-gray-50 z-10">NISN</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase min-w-[200px] sticky left-36 bg-gray-50 z-10">Nama Siswa</th>
                            @foreach ($mapels as $mapel)
                                <th class="px-2 py-3 text-center text-xs font-semibold text-gray-600 uppercase whitespace-nowrap min-w-[80px]"
                                    title="{{ $mapel->nama_mapel }}">
                                    {{ $mapel->short_name }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($siswas as $idx => $siswa)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 text-gray-600 sticky left-0 bg-white hover:bg-gray-50">{{ $idx + 1 }}</td>
                                <td class="px-3 py-2 text-gray-600 font-mono text-xs sticky left-10 bg-white hover:bg-gray-50">{{ $siswa->nisn ?: '-' }}</td>
                                <td class="px-3 py-2 text-gray-900 sticky left-36 bg-white hover:bg-gray-50">{{ $siswa->nama_lengkap }}</td>
                                @foreach ($mapels as $mapel)
                                    <td class="px-1 py-1 text-center" title="{{ $mapel->nama_mapel }}">
                                        <input type="number" step="0.01" min="0" max="100"
                                            wire:model="grid.{{ $siswa->id }}.{{ $mapel->id }}.{{ $semesterTab }}"
                                            class="w-16 px-1 py-1 text-center rounded-lg border border-gray-200 focus:ring-1 focus:ring-gray-900 focus:border-transparent text-sm">
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 3 + $mapels->count() }}" class="px-6 py-12 text-center text-gray-500">
                                    Tidak ada siswa kelas 6 aktif ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($siswas->count() > 0 && $mapels->count() > 0)
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div class="text-xs text-gray-500">
                        Nilai: 0 - 100, boleh desimal (misal 89.5). Kosongkan jika belum ada nilai. Menyimpan nilai untuk
                        <strong>{{ $semesterOptions[$semesterTab] ?? '-' }}</strong> saja. Arahkan kursor ke header
                        untuk melihat nama mapel lengkap.
                    </div>
                    <button wire:click="saveRaport" wire:loading.attr="disabled"
                        class="px-4 py-2 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium disabled:opacity-60 whitespace-nowrap">
                        <span wire:loading.remove wire:target="saveRaport">Simpan {{ $semesterOptions[$semesterTab] ?? '' }}</span>
                        <span wire:loading wire:target="saveRaport">Menyimpan...</span>
                    </button>
                </div>
            @endif
        </div>

        {{-- Ringkasan kelengkapan per siswa --}}
        @if ($siswas->count() > 0 && $mapels->count() > 0)
            <div class="mt-4 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-sm">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        Pindahlah antar semester di atas untuk menginput nilai semester lainnya. Sistem menyimpan per
                        semester tanpa menghapus nilai semester lain yang sudah tersimpan.
                    </div>
                </div>
            </div>
        @endif
    @endif

    {{-- ============================ TAB: UM (mapel ke samping) ============================ --}}
    @if ($tab === 'um')
        {{-- Toolbar search --}}
        <div class="flex flex-col sm:flex-row justify-between gap-3 mb-4">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="searchSiswa" placeholder="Cari nama/NISN siswa..."
                    class="pl-10 pr-4 py-2 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent w-72 text-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <div class="text-sm text-gray-500 self-center">
                {{ $siswas->count() }} siswa &middot; {{ $mapels->count() }} mapel
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase sticky left-0 bg-gray-50 z-10">No</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase sticky left-10 bg-gray-50 z-10">NISN</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase min-w-[200px] sticky left-36 bg-gray-50 z-10">Nama Siswa</th>
                            @foreach ($mapels as $mapel)
                                <th class="px-2 py-3 text-center text-xs font-semibold text-gray-600 uppercase whitespace-nowrap min-w-[80px]"
                                    title="{{ $mapel->nama_mapel }}">
                                    {{ $mapel->short_name }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($siswas as $idx => $siswa)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 text-gray-600 sticky left-0 bg-white hover:bg-gray-50">{{ $idx + 1 }}</td>
                                <td class="px-3 py-2 text-gray-600 font-mono text-xs sticky left-10 bg-white hover:bg-gray-50">{{ $siswa->nisn ?: '-' }}</td>
                                <td class="px-3 py-2 text-gray-900 sticky left-36 bg-white hover:bg-gray-50">{{ $siswa->nama_lengkap }}</td>
                                @foreach ($mapels as $mapel)
                                    <td class="px-1 py-1 text-center" title="{{ $mapel->nama_mapel }}">
                                        <input type="number" step="0.01" min="0" max="100"
                                            wire:model="grid.{{ $siswa->id }}.{{ $mapel->id }}.nilai_um"
                                            class="w-16 px-1 py-1 text-center rounded-lg border border-gray-200 focus:ring-1 focus:ring-gray-900 focus:border-transparent text-sm">
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 3 + $mapels->count() }}" class="px-6 py-12 text-center text-gray-500">
                                    Tidak ada siswa kelas 6 aktif ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($siswas->count() > 0 && $mapels->count() > 0)
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
                    <div class="text-xs text-gray-500">Nilai UM: 0 - 100, boleh desimal. Kosongkan jika belum ada nilai.</div>
                    <button wire:click="saveUm" wire:loading.attr="disabled"
                        class="px-4 py-2 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium disabled:opacity-60 whitespace-nowrap">
                        <span wire:loading.remove wire:target="saveUm">Simpan Nilai UM</span>
                        <span wire:loading wire:target="saveUm">Menyimpan...</span>
                    </button>
                </div>
            @endif
        </div>
    @endif

    {{-- ============================ TAB: NILAI RATA-RATA (final: 70% raport + 30% UM) ============================ --}}
    @if ($tab === 'rekap')
        @php
            $calc = app(\App\Services\NilaiIjazahCalculator::class);
        @endphp

        <div class="flex flex-col sm:flex-row justify-between gap-3 mb-4">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="searchSiswa" placeholder="Cari nama/NISN siswa..."
                    class="pl-10 pr-4 py-2 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent w-72 text-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-xs text-gray-500 hidden md:block">
                    Nilai Akhir = (Raport &times; 70%) + (UM &times; 30%)
                </div>
                <a href="{{ route('nilai-ijazah.export-rekap', $tahunAjaran->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition-colors whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"></path>
                    </svg>
                    Download Excel
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase sticky left-0 bg-gray-50 z-10">No</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase sticky left-10 bg-gray-50 z-10">NISN</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase min-w-[200px] sticky left-36 bg-gray-50 z-10">Nama Siswa</th>
                            @foreach ($mapels as $mapel)
                                <th class="px-2 py-3 text-center text-xs font-semibold text-gray-600 uppercase whitespace-nowrap min-w-[90px]"
                                    title="{{ $mapel->nama_mapel }} — Nilai Akhir Ijazah">
                                    {{ $mapel->short_name }}
                                </th>
                            @endforeach
                            <th class="px-2 py-3 text-center text-xs font-semibold text-blue-700 uppercase whitespace-nowrap min-w-[100px] bg-blue-50">
                                Rata-rata Raport
                            </th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-purple-700 uppercase whitespace-nowrap min-w-[90px] bg-purple-50">
                                Rata-rata UM
                            </th>
                            <th class="px-2 py-3 text-center text-xs font-semibold text-green-800 uppercase whitespace-nowrap min-w-[110px] bg-green-50">
                                Rata-rata Akhir
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($siswas as $idx => $siswa)
                            @php
                                $sumRaport = 0.0; $countRaport = 0;
                                $sumUm = 0.0; $countUm = 0;
                                $sumFinal = 0.0; $countFinal = 0;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 text-gray-600 sticky left-0 bg-white hover:bg-gray-50">{{ $idx + 1 }}</td>
                                <td class="px-3 py-2 text-gray-600 font-mono text-xs sticky left-10 bg-white hover:bg-gray-50">{{ $siswa->nisn ?: '-' }}</td>
                                <td class="px-3 py-2 text-gray-900 sticky left-36 bg-white hover:bg-gray-50">{{ $siswa->nama_lengkap }}</td>
                                @foreach ($mapels as $mapel)
                                    @php
                                        $row = $grid[$siswa->id][$mapel->id] ?? [];
                                        $nilaiRaport = [
                                            $row['kelas_4_semester_1'] ?? null,
                                            $row['kelas_4_semester_2'] ?? null,
                                            $row['kelas_5_semester_1'] ?? null,
                                            $row['kelas_5_semester_2'] ?? null,
                                            $row['kelas_6_semester_1'] ?? null,
                                        ];
                                        $rata = $calc->rataRataRaport($nilaiRaport);
                                        $um = isset($row['nilai_um']) && $row['nilai_um'] !== '' && $row['nilai_um'] !== null
                                            ? (float) $row['nilai_um']
                                            : null;
                                        $final = $calc->nilaiIjazah($rata, $um);

                                        if ($rata !== null) {
                                            $sumRaport += $rata;
                                            $countRaport++;
                                        }
                                        if ($um !== null) {
                                            $sumUm += $um;
                                            $countUm++;
                                        }
                                        if ($final !== null) {
                                            $sumFinal += $final;
                                            $countFinal++;
                                        }
                                    @endphp
                                    <td class="px-2 py-2 text-center"
                                        title="{{ $mapel->nama_mapel }} — Raport: {{ $rata !== null ? number_format($rata, 2) : '-' }}, UM: {{ $um !== null ? number_format($um, 2) : '-' }}">
                                        @if ($final !== null)
                                            <span class="inline-block px-2 py-1 rounded bg-green-50 font-semibold text-green-900">
                                                {{ number_format($final, 2) }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">-</span>
                                        @endif
                                    </td>
                                @endforeach

                                {{-- Rata-rata Raport (rata-rata dari rata2 raport tiap mapel yang lengkap) --}}
                                <td class="px-2 py-2 text-center bg-blue-50">
                                    @if ($countRaport > 0)
                                        <span class="font-semibold text-blue-900">{{ number_format($sumRaport / $countRaport, 2) }}</span>
                                        <div class="text-[10px] text-blue-700">{{ $countRaport }}/{{ $mapels->count() }} mapel</div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">-</span>
                                    @endif
                                </td>

                                {{-- Rata-rata UM (rata-rata dari nilai UM tiap mapel yang terisi) --}}
                                <td class="px-2 py-2 text-center bg-purple-50">
                                    @if ($countUm > 0)
                                        <span class="font-semibold text-purple-900">{{ number_format($sumUm / $countUm, 2) }}</span>
                                        <div class="text-[10px] text-purple-700">{{ $countUm }}/{{ $mapels->count() }} mapel</div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">-</span>
                                    @endif
                                </td>

                                {{-- Rata-rata Akhir (rata-rata dari nilai akhir 70% raport + 30% UM tiap mapel yang lengkap) --}}
                                <td class="px-2 py-2 text-center bg-green-50">
                                    @if ($countFinal > 0)
                                        <span class="font-semibold text-green-900">{{ number_format($sumFinal / $countFinal, 2) }}</span>
                                        <div class="text-[10px] text-green-700">{{ $countFinal }}/{{ $mapels->count() }} mapel</div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 6 + $mapels->count() }}" class="px-6 py-12 text-center text-gray-500">
                                    Tidak ada siswa kelas 6 aktif ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($siswas->count() > 0 && $mapels->count() > 0)
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 text-xs text-gray-500 space-y-1">
                    <div>Tabel read-only. Nilai di setiap sel mapel = <strong>(Rata-rata Raport × 70%) + (Nilai UM × 30%)</strong>.</div>
                    <div>
                        <span class="inline-block px-1.5 rounded bg-blue-50 text-blue-800 font-semibold">Rata-rata Raport</span>
                        = rata-rata dari rata-rata raport tiap mapel yang komponennya lengkap (belum pakai bobot).
                    </div>
                    <div>
                        <span class="inline-block px-1.5 rounded bg-purple-50 text-purple-800 font-semibold">Rata-rata UM</span>
                        = rata-rata nilai UM tiap mapel yang sudah terisi.
                    </div>
                    <div>
                        <span class="inline-block px-1.5 rounded bg-green-50 text-green-800 font-semibold">Rata-rata Akhir</span>
                        = rata-rata dari nilai akhir tiap mapel yang sudah lengkap (sudah termasuk bobot 70%/30%).
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- ============================ TAB: CETAK ============================ --}}
    @if ($tab === 'cetak')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Card: Cetak Cover Depan --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Cetak Cover Depan Ijazah (Dummy)</h3>
                        <p class="text-sm text-gray-500">Cover bertanda "DOKUMEN DUMMY - BUKAN IJAZAH RESMI".</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <a href="{{ route('nilai-ijazah.print-cover', $tahunAjaran->id) }}" target="_blank"
                        class="flex items-center justify-between px-4 py-3 rounded-xl bg-gray-50 hover:bg-gray-100 text-sm text-gray-900">
                        <span class="font-medium">Cetak untuk Semua Siswa</span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Card: Cetak Nilai --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-6h6v6m-3 0h.01M4 6a2 2 0 012-2h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Cetak Nilai Ijazah</h3>
                        <p class="text-sm text-gray-500">Nilai Ijazah = 70% Rata-rata Raport + 30% UM.</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <a href="{{ route('nilai-ijazah.print-nilai', $tahunAjaran->id) }}" target="_blank"
                        class="flex items-center justify-between px-4 py-3 rounded-xl bg-gray-50 hover:bg-gray-100 text-sm text-gray-900">
                        <span class="font-medium">Cetak untuk Semua Siswa</span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Cetak per siswa --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 lg:col-span-2">
                <h3 class="font-semibold text-gray-900 mb-3">Cetak Per Siswa</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">NISN</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Nama Siswa</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Kelas</th>
                                <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($siswas as $idx => $siswa)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 text-gray-600">{{ $idx + 1 }}</td>
                                    <td class="px-3 py-2 text-gray-600 font-mono text-xs">{{ $siswa->nisn ?: '-' }}</td>
                                    <td class="px-3 py-2 text-gray-900">{{ $siswa->nama_lengkap }}</td>
                                    <td class="px-3 py-2 text-gray-700">{{ $siswa->tingkat_rombel ?: '-' }}</td>
                                    <td class="px-3 py-2 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('nilai-ijazah.print-cover', ['tahunAjaran' => $tahunAjaran->id, 'siswa' => $siswa->id]) }}"
                                                target="_blank"
                                                class="px-3 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-medium">
                                                Cetak Cover
                                            </a>
                                            <a href="{{ route('nilai-ijazah.print-nilai', ['tahunAjaran' => $tahunAjaran->id, 'siswa' => $siswa->id]) }}"
                                                target="_blank"
                                                class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-800 text-xs font-medium">
                                                Cetak Nilai
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-8 text-center text-gray-500">Tidak ada siswa kelas 6 aktif ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>