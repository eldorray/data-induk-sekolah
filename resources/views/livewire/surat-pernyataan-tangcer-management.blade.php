<div class="animate-fade-up">
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

    {{-- Tab Navigation --}}
    <div class="flex gap-2 mb-6">
        <button wire:click="$set('activeTab', 'buat')"
            class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $activeTab === 'buat' ? 'bg-gray-900 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Buat Surat
        </button>
        <button wire:click="$set('activeTab', 'riwayat')"
            class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $activeTab === 'riwayat' ? 'bg-gray-900 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Riwayat Surat
        </button>
    </div>

    {{-- TAB: BUAT SURAT --}}
    @if ($activeTab === 'buat')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left: Form Settings --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden sticky top-6">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-sm font-semibold text-gray-900">Pengaturan Surat</h3>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        {{-- Nomor Surat --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat</label>
                            <input type="text" wire:model="nomor_surat" placeholder="512/MIDH/III/2026"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                        </div>

                        {{-- Tahun Anggaran --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Anggaran</label>
                            <input type="text" wire:model="tahun_anggaran"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                        </div>

                        {{-- Semester --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                            <select wire:model="semester"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>

                        {{-- Isi Surat --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Isi Surat (Paragraf
                                Keterangan)</label>
                            <textarea wire:model="isi_surat" rows="5"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm"></textarea>
                        </div>

                        {{-- Isi Tujuan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Isi Tujuan (Paragraf
                                Penutup)</label>
                            <textarea wire:model="isi_tujuan" rows="4"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm"></textarea>
                        </div>

                        {{-- Tanggal Surat --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat</label>
                            <input type="date" wire:model="tanggal_surat"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                        </div>

                        {{-- Generate Button --}}
                        <button wire:click="generateSurat" wire:loading.attr="disabled"
                            class="w-full px-4 py-3 bg-gray-900 text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition-all disabled:opacity-50 flex items-center justify-center gap-2">
                            <svg wire:loading wire:target="generateSurat" class="animate-spin h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Buat Surat ({{ count($selectedSiswaIds) }} siswa dipilih)
                        </button>
                    </div>
                </div>
            </div>

            {{-- Right: Siswa Checklist --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <h3 class="text-sm font-semibold text-gray-900">Pilih Siswa</h3>
                            <div class="flex items-center gap-3">
                                {{-- Jenjang Toggle --}}
                                <div class="flex gap-2">
                                    <button wire:click="$set('filterJenjang', 'mi')"
                                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $filterJenjang === 'mi' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                        MI
                                    </button>
                                    <button wire:click="$set('filterJenjang', 'smp')"
                                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $filterJenjang === 'smp' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                        SMP
                                    </button>
                                </div>
                                {{-- Search --}}
                                <div class="relative">
                                    <input type="text" wire:model.live.debounce.300ms="searchSiswa"
                                        placeholder="Cari siswa..."
                                        class="pl-9 pr-4 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-gray-900 focus:border-transparent w-48">
                                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Select All --}}
                    <div class="px-6 py-3 border-b border-gray-100 bg-gray-50/50">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model.live="selectAll"
                                class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                            <span class="text-sm font-medium text-gray-700">Pilih Semua ({{ count($siswas) }}
                                siswa)</span>
                        </label>
                    </div>

                    {{-- Siswa List --}}
                    <div class="divide-y divide-gray-50 max-h-[60vh] overflow-y-auto">
                        @forelse ($siswas as $siswa)
                            <label
                                class="flex items-center gap-4 px-6 py-3 hover:bg-gray-50 cursor-pointer transition-colors">
                                <input type="checkbox" wire:model.live="selectedSiswaIds" value="{{ $siswa->id }}"
                                    class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $siswa->nama_lengkap }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        NISN: {{ $siswa->nisn ?? '-' }} |
                                        Kelas: {{ $siswa->tingkat_rombel ?? '-' }} |
                                        Wali: {{ $siswa->nama_wali ?? '-' }}
                                    </p>
                                </div>
                            </label>
                        @empty
                            <div class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                <p class="text-sm">Tidak ada siswa ditemukan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- TAB: RIWAYAT --}}
    @if ($activeTab === 'riwayat')
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <h3 class="text-sm font-semibold text-gray-900">Riwayat Surat</h3>
                    <div class="flex items-center gap-3">
                        {{-- Filter Tahun Anggaran --}}
                        <select wire:model.live="filterTahunAnggaran"
                            class="px-3 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                            <option value="">Semua Tahun</option>
                            @foreach ($tahunAnggaranOptions as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                        {{-- Search --}}
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="searchRiwayat"
                                placeholder="Cari no. surat..."
                                class="pl-9 pr-4 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-gray-900 focus:border-transparent w-48">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        {{-- Export All --}}
                        <a href="{{ route('surat-pernyataan-tangcer.export-all', ['tahun_anggaran' => $filterTahunAnggaran]) }}"
                            target="_blank"
                            class="px-4 py-2 bg-gray-900 text-white rounded-xl text-sm font-semibold hover:bg-gray-800 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Export All PDF
                        </a>
                        {{-- Delete All --}}
                        <button wire:click="openDeleteAllModal"
                            class="px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-all">
                            Hapus Semua
                        </button>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Siswa
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No. Surat
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tahun
                                Anggaran</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Semester
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($riwayat as $i => $surat)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3 text-sm text-gray-500">{{ $i + 1 }}</td>
                                <td class="px-6 py-3 text-sm font-medium text-gray-900">
                                    {{ $surat->siswa_nama }}
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600">{{ $surat->nomor_surat }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600">{{ $surat->tahun_anggaran }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600">{{ $surat->semester }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600">
                                    {{ $surat->tanggal_surat?->format('d/m/Y') ?? '-' }}
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('surat-pernyataan-tangcer.print', $surat->id) }}"
                                            target="_blank"
                                            class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold hover:bg-gray-200 transition-all">
                                            Print
                                        </a>
                                        <button wire:click="openDeleteModal({{ $surat->id }})"
                                            class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-100 transition-all">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-sm">Belum ada surat pernyataan TANGCER.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Delete Modal --}}
    @if ($showDeleteModal)
        <div x-data x-teleport="#modal-portal">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus Surat</h3>
                    <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin menghapus surat pernyataan ini?
                        Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="flex items-center justify-end gap-3">
                        <button wire:click="closeModal"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-all">
                            Batal
                        </button>
                        <button wire:click="delete"
                            class="px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-all">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete All Modal --}}
    @if ($showDeleteAllModal)
        <div x-data x-teleport="#modal-portal">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus Semua Surat</h3>
                    <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin menghapus semua surat pernyataan
                        TANGCER{{ $filterTahunAnggaran ? ' tahun anggaran ' . $filterTahunAnggaran : '' }}? Tindakan
                        ini tidak dapat dibatalkan.</p>
                    <div class="flex items-center justify-end gap-3">
                        <button wire:click="closeModal"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-all">
                            Batal
                        </button>
                        <button wire:click="deleteAll"
                            class="px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-all">
                            Hapus Semua
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
