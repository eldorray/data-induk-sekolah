<div class="animate-fade-up">
    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form --}}
        <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-900 mb-4">Data Surat</h2>

            <div class="space-y-4">
                @foreach ([
        'nomor_surat' => 'Nomor Surat',
        'tanggal_surat' => 'Tanggal Surat',
        'tahun_pelajaran' => 'Tahun Pelajaran',
        'nama_madrasah' => 'Nama Madrasah',
        'nama_kepala' => 'Nama Kepala Madrasah',
        'kota' => 'Kota (tempat surat)',
        'nama_pengawas' => 'Nama Pengawas',
        'nip_pengawas' => 'NIP Pengawas',
    ] as $field => $label)
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">{{ $label }}</label>
                        <input type="{{ $field === 'tanggal_surat' ? 'date' : 'text' }}" wire:model="{{ $field }}"
                            class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                        @error($field)
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                @endforeach
            </div>

            <div class="flex gap-2 mt-6">
                <button wire:click="save"
                    class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium">
                    Simpan
                </button>
                <a href="{{ route('surat-pernyataan-rombel.print') }}" target="_blank"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-900 text-sm font-medium">
                    Cetak PDF
                </a>
            </div>
            <p class="text-xs text-gray-500 mt-3">Simpan dulu sebelum cetak agar data surat ikut terbaru.</p>
        </div>

        {{-- Preview rekap --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-6 overflow-x-auto">
            <h2 class="text-sm font-semibold text-gray-900 mb-1">Rekap Rombongan Belajar</h2>
            <p class="text-xs text-gray-500 mb-4">Otomatis dari data siswa MI berstatus Aktif.</p>

            <table class="w-full text-sm border border-gray-200">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="border border-gray-200 px-3 py-2 text-left">Kelas</th>
                        <th class="border border-gray-200 px-3 py-2">Rombel</th>
                        <th class="border border-gray-200 px-3 py-2">L</th>
                        <th class="border border-gray-200 px-3 py-2">P</th>
                        <th class="border border-gray-200 px-3 py-2">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekap['tingkat'] as $label => $row)
                        <tr>
                            <td class="border border-gray-200 px-3 py-2 font-medium">{{ $label }}</td>
                            <td class="border border-gray-200 px-3 py-2 text-center">{{ $row['rombel'] }}</td>
                            <td class="border border-gray-200 px-3 py-2 text-center">{{ $row['l'] }}</td>
                            <td class="border border-gray-200 px-3 py-2 text-center">{{ $row['p'] }}</td>
                            <td class="border border-gray-200 px-3 py-2 text-center">{{ $row['total'] }}</td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-semibold">
                        <td class="border border-gray-200 px-3 py-2">JUMLAH</td>
                        <td class="border border-gray-200 px-3 py-2 text-center">{{ $rekap['total']['rombel'] }}</td>
                        <td class="border border-gray-200 px-3 py-2 text-center">{{ $rekap['total']['l'] }}</td>
                        <td class="border border-gray-200 px-3 py-2 text-center">{{ $rekap['total']['p'] }}</td>
                        <td class="border border-gray-200 px-3 py-2 text-center">{{ $rekap['total']['total'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
