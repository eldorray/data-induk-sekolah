<div class="animate-fade-up">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 mb-6">
        <div class="flex flex-wrap items-center gap-2">
            <select wire:model.live="lpjId"
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm">
                <option value="">-- Pilih LPJ Insentif --</option>
                @foreach ($lpjs as $l)
                    <option value="{{ $l->id }}">{{ $l->periode }} {{ $l->tahun }}</option>
                @endforeach
            </select>

            <select wire:model.live="bulan"
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm">
                @foreach (range(1, 12) as $b)
                    <option value="{{ $b }}">
                        {{ \App\Models\HariLibur::namaBulan($b) }} {{ $tahun }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-2">
            <button wire:click="openLiburModal"
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium">
                + Hari Libur
            </button>
            @if ($lpjId)
                <a href="{{ route('absensi-insentif.print', ['lpj' => $lpjId, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
                    target="_blank"
                    class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium">
                    Cetak PDF
                </a>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tahun Ajaran</p>
            <p class="text-xl font-semibold text-gray-900 mt-1">{{ $tahunAjaran }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Bulan</p>
            <p class="text-xl font-semibold text-gray-900 mt-1">{{ $namaBulan }} {{ $tahun }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Hari</p>
            <p class="text-xl font-semibold text-gray-900 mt-1">{{ $jumlahHari }} hari</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Penerima</p>
            <p class="text-xl font-semibold text-gray-900 mt-1">{{ $lpj?->penerimas->count() ?? 0 }} orang</p>
        </div>
    </div>

    @if ($hariLibur->isNotEmpty())
        <div class="bg-white rounded-2xl border border-gray-200 p-4 mb-6">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Hari Libur Bulan Ini
                (selain Minggu)</p>
            <div class="flex flex-wrap gap-2">
                @foreach ($hariLibur as $h)
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                        {{ $h->tanggal->format('d') }} {{ $namaBulan }} — {{ $h->nama }}
                        <button wire:click="deleteLibur({{ $h->id }})" wire:loading.attr="disabled"
                            class="text-red-500 hover:text-red-700 font-bold">&times;</button>
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    @if ($lpj)
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <p class="text-sm font-semibold text-gray-900">BULAN : {{ strtoupper($namaBulan) }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Sel abu-abu = hari Minggu / hari libur</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-2 py-3 border border-gray-200 text-left font-semibold text-gray-600">No</th>
                            <th class="px-2 py-3 border border-gray-200 text-left font-semibold text-gray-600">Nama</th>
                            <th class="px-2 py-3 border border-gray-200 text-left font-semibold text-gray-600">NUPTK/PegID
                            </th>
                            <th class="px-2 py-3 border border-gray-200 text-left font-semibold text-gray-600">Jabatan
                            </th>
                            <th colspan="{{ $jumlahHari }}"
                                class="px-2 py-1.5 border border-gray-200 text-center font-semibold text-gray-600">
                                Tanggal</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="border border-gray-200"></th>
                            @foreach (range(1, $jumlahHari) as $d)
                                <th
                                    class="px-0 py-1.5 border border-gray-200 text-center font-semibold {{ isset($arsir[$d]) ? 'bg-gray-300' : '' }}">
                                    {{ $d }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($lpj->penerimas as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 py-2 border border-gray-200 text-center text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-2 py-2 border border-gray-200 font-medium text-gray-900 whitespace-nowrap">{{ $p->nama }}</td>
                                <td class="px-2 py-2 border border-gray-200 font-mono text-gray-600 whitespace-nowrap">{{ $p->nip_pegid_nuptk ?? '-' }}</td>
                                <td class="px-2 py-2 border border-gray-200 text-gray-600">{{ $p->jabatan }}</td>
                                @foreach (range(1, $jumlahHari) as $d)
                                    <td
                                        class="px-0 py-2 border border-gray-200 min-w-[1.4rem] {{ isset($arsir[$d]) ? 'bg-gray-300' : '' }}">
                                        &nbsp;</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 4 + $jumlahHari }}" class="px-6 py-12 text-center text-gray-500">LPJ
                                    Insentif ini belum punya daftar penerima.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-500">
            Pilih LPJ Insentif untuk menampilkan daftar hadir.
        </div>
    @endif

    @if ($showLiburModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeLiburModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl">
                        <form wire:submit="saveLibur">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-semibold text-gray-900">Tambah Hari Libur</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                    <input type="date" wire:model="liburTanggal"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                    @error('liburTanggal')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Libur</label>
                                    <input type="text" wire:model="liburNama" placeholder="mis. Idul Adha"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                    @error('liburNama')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <p class="text-xs text-gray-400">Hari Minggu otomatis diarsir tanpa perlu dicatat.</p>
                            </div>
                            <div class="border-t border-gray-200 px-6 py-4 flex justify-end gap-2">
                                <button type="button" wire:click="closeLiburModal"
                                    class="px-4 py-2 rounded-xl border border-gray-200 text-sm">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
