<div class="animate-fade-up space-y-6">
    @if (session('success'))
        <div class="rounded-xl border border-madrasah-200 bg-madrasah-50 px-5 py-4 text-sm font-medium text-madrasah-800">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-800">{{ session('error') }}</div>
    @endif

    {{-- Langkah 1: upload --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-sm text-gray-600 mb-4">
            Di EMIS, buka menu Peserta Didik lalu unduh <b>Daftar siswa</b> (Excel). Unggah file itu di sini.
            Data EMIS dianggap <b>benar</b>: data induk akan disamakan dengan EMIS. Sel yang kosong di EMIS tidak menghapus data induk.
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">Jenjang</label>
                <select wire:model.live="jenjang" class="w-full h-11 px-3 rounded-lg border border-gray-300 text-sm">
                    <option value="mi">MI</option>
                    <option value="smp">SMP</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">File unduhan EMIS</label>
                <input type="file" wire:model="file" accept=".xlsx,.xls" class="w-full text-sm" />
                @error('file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                <div wire:loading wire:target="file" class="mt-1 text-xs text-gray-500">Mengunggah...</div>
            </div>
            <div>
                <button type="button" wire:click="bandingkan" wire:loading.attr="disabled" wire:target="bandingkan,file"
                    class="w-full h-11 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 disabled:opacity-50">
                    <span wire:loading.remove wire:target="bandingkan">Bandingkan</span>
                    <span wire:loading wire:target="bandingkan">Membandingkan...</span>
                </button>
            </div>
        </div>
    </div>

    @if ($hasil)
        {{-- Ringkasan --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach ([
                ['Sudah sama', $hasil['sama'], 'text-madrasah-700'],
                ['Beda isi', count($hasil['beda']), 'text-amber-700'],
                ['Baru dari EMIS', count($hasil['baru']), 'text-blue-700'],
                ['Tidak ada di EMIS', count($hasil['tidak_di_emis']), 'text-red-700'],
            ] as [$label, $jumlah, $warna])
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-2xl font-medium {{ $warna }}">{{ $jumlah }}</p>
                    <p class="text-xs text-gray-500">{{ $label }}</p>
                </div>
            @endforeach
        </div>

        @if ($hasil['beda'] || $hasil['baru'])
            <div class="bg-white rounded-xl border border-gray-200 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <p class="text-sm text-gray-700">
                    Terapkan akan <b>memperbarui {{ count($hasil['beda']) }}</b> siswa dan <b>menambah {{ count($hasil['baru']) }}</b> siswa di data induk {{ strtoupper($jenjang) }}.
                </p>
                <button type="button" wire:click="terapkan" wire:loading.attr="disabled"
                    wire:confirm="Samakan data induk {{ strtoupper($jenjang) }} dengan EMIS? Data lama yang beda akan ditimpa."
                    class="h-11 px-5 rounded-lg bg-madrasah-600 text-white text-sm font-medium hover:bg-madrasah-700 disabled:opacity-50">
                    Terapkan dari EMIS
                </button>
            </div>
        @endif

        @if ($hasil['beda'])
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <h3 class="px-5 py-3 text-sm font-medium text-gray-900 border-b border-gray-200">Beda isi ({{ count($hasil['beda']) }})</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-xs text-gray-500 text-left">
                            <tr><th class="px-5 py-2">Siswa</th><th class="px-5 py-2">Kolom</th><th class="px-5 py-2">Data induk</th><th class="px-5 py-2">EMIS</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($hasil['beda'] as $b)
                                @foreach ($b['perubahan'] as $kolom => $p)
                                    <tr>
                                        <td class="px-5 py-2 font-medium text-gray-900">{{ $loop->first ? $b['nama'] : '' }}</td>
                                        <td class="px-5 py-2 text-gray-600">{{ ucfirst(str_replace('_', ' ', $kolom)) }}</td>
                                        <td class="px-5 py-2 text-red-700 line-through">{{ $p['induk'] !== '' ? $p['induk'] : '(kosong)' }}</td>
                                        <td class="px-5 py-2 text-madrasah-700">{{ $p['emis'] }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if ($hasil['baru'])
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <h3 class="px-5 py-3 text-sm font-medium text-gray-900 border-b border-gray-200">Ada di EMIS, belum ada di data induk ({{ count($hasil['baru']) }})</h3>
                <ul class="divide-y divide-gray-100 text-sm">
                    @foreach ($hasil['baru'] as $row)
                        <li class="px-5 py-2"><span class="font-medium text-gray-900">{{ $row['nama_lengkap'] }}</span> <span class="text-gray-500">· {{ $row['tingkat_rombel'] ?? '' }} · NISN {{ ($row['nisn'] ?? '') !== '' ? $row['nisn'] : '-' }}</span></li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($hasil['tidak_di_emis'])
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <h3 class="px-5 py-3 text-sm font-medium text-gray-900 border-b border-gray-200">Aktif di data induk, tidak ada di EMIS ({{ count($hasil['tidak_di_emis']) }})</h3>
                <p class="px-5 pt-3 text-xs text-gray-500">Tidak diubah otomatis. Cek satu per satu: mungkin sudah pindah/keluar (ubah statusnya di menu Siswa), atau belum diinput di EMIS.</p>
                <ul class="divide-y divide-gray-100 text-sm">
                    @foreach ($hasil['tidak_di_emis'] as $s)
                        <li class="px-5 py-2"><span class="font-medium text-gray-900">{{ $s['nama'] }}</span> <span class="text-gray-500">· {{ $s['tingkat_rombel'] }}</span></li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (! $hasil['beda'] && ! $hasil['baru'] && ! $hasil['tidak_di_emis'])
            <div class="rounded-xl border border-madrasah-200 bg-madrasah-50 px-5 py-4 text-sm font-medium text-madrasah-800">
                Data induk {{ strtoupper($jenjang) }} sudah sama dengan EMIS untuk kelas di file ini.
            </div>
        @endif
    @endif
</div>
