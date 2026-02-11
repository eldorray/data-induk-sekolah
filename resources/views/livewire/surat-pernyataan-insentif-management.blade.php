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
    <div class="flex gap-1 mb-6 bg-gray-100 rounded-xl p-1 w-fit">
        <button wire:click="$set('activeTab', 'buat')"
            class="px-5 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $activeTab === 'buat' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
            Buat Surat
        </button>
        <button wire:click="$set('activeTab', 'riwayat')"
            class="px-5 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $activeTab === 'riwayat' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
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
                        {{-- Jabatan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                            <input type="text" wire:model="jabatan"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                        </div>
                        {{-- Unit Kerja --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja</label>
                            <input type="text" wire:model="unit_kerja"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                        </div>
                        {{-- Alamat --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Unit Kerja</label>
                            <textarea wire:model="alamat_unit_kerja" rows="2"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm"></textarea>
                        </div>
                        {{-- Sumber Insentif --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sumber Insentif</label>
                            <input type="text" wire:model="sumber_insentif"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                        </div>
                        {{-- Bulan/Tahun --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bulan/Tahun</label>
                            <input type="text" wire:model="bulan_tahun" placeholder="Januari 2025"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                        </div>
                        {{-- Tanggal --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat</label>
                            <input type="date" wire:model="tanggal_surat"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                        </div>

                        {{-- Generate Button --}}
                        <button wire:click="generateSurat" wire:loading.attr="disabled"
                            class="w-full px-4 py-3 rounded-xl bg-gray-900 hover:bg-gray-800 disabled:bg-gray-400 text-white text-sm font-medium flex items-center justify-center gap-2 transition-colors">
                            <svg wire:loading.remove wire:target="generateSurat" class="w-4 h-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <svg wire:loading wire:target="generateSurat" class="w-4 h-4 animate-spin" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Buat Surat ({{ count($selectedGuruIds) }} guru dipilih)
                        </button>
                    </div>
                </div>
            </div>

            {{-- Right: Guru Checklist --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <h3 class="text-sm font-semibold text-gray-900">Pilih Guru</h3>
                            <div class="flex items-center gap-3">
                                {{-- Jenjang Toggle --}}
                                <div class="flex gap-2">
                                    <button wire:click="$set('filterJenjang', 'mi')"
                                        class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ $filterJenjang === 'mi' ? 'bg-gray-900 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                                        MI
                                    </button>
                                    <button wire:click="$set('filterJenjang', 'smp')"
                                        class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ $filterJenjang === 'smp' ? 'bg-gray-900 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                                        SMP
                                    </button>
                                </div>
                                {{-- Search --}}
                                <div class="relative">
                                    <input type="text" wire:model.live.debounce.300ms="searchGuru"
                                        placeholder="Cari guru..."
                                        class="pl-8 pr-4 py-1.5 rounded-lg border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent w-48 text-sm">
                                    <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2"
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
                            <span class="text-sm font-medium text-gray-700">Pilih Semua ({{ count($gurus) }}
                                guru)</span>
                        </label>
                    </div>

                    {{-- Guru List --}}
                    <div class="divide-y divide-gray-50 max-h-[60vh] overflow-y-auto">
                        @forelse($gurus as $guru)
                            <label
                                class="flex items-center gap-4 px-6 py-3 hover:bg-gray-50 cursor-pointer transition-colors {{ in_array((string) $guru->id, $selectedGuruIds) ? 'bg-blue-50/50' : '' }}">
                                <input type="checkbox" wire:model.live="selectedGuruIds" value="{{ $guru->id }}"
                                    class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900">{{ $guru->full_name_with_title }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $guru->status_pegawai_label }}
                                        @if ($guru->nip)
                                            · NIP: {{ $guru->nip }}
                                        @endif
                                        @if ($guru->nuptk)
                                            · NUPTK: {{ $guru->nuptk }}
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                                    </path>
                                </svg>
                                <p class="text-sm">Tidak ada guru yang ditemukan</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- TAB: RIWAYAT SURAT --}}
    @if ($activeTab === 'riwayat')
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div
                class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h3 class="text-sm font-semibold text-gray-900">Riwayat Surat Pernyataan Insentif</h3>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="searchRiwayat"
                        placeholder="Cari bulan/tahun..."
                        class="pl-8 pr-4 py-1.5 rounded-lg border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent w-48 text-sm">
                    <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                No</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nama Guru</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Jabatan</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Unit Kerja</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Bulan/Tahun</th>
                            <th
                                class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($riwayat as $index => $surat)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $surat->guru_nama }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ strtoupper(str_replace('guru_', '', $surat->guru_type)) }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $surat->jabatan }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $surat->unit_kerja }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $surat->bulan_tahun }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('surat-pernyataan-insentif.print', $surat->id) }}"
                                            target="_blank"
                                            class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors"
                                            title="Print PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                        </a>
                                        <button wire:click="openDeleteModal({{ $surat->id }})"
                                            class="p-2 rounded-lg hover:bg-red-50 text-gray-600 hover:text-red-600 transition-colors"
                                            title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-sm">Belum ada riwayat surat pernyataan insentif</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($showDeleteModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl">
                        <div class="p-6 text-center">
                            <svg class="w-16 h-16 mx-auto text-red-500 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus Surat Pernyataan Insentif?
                            </h3>
                            <p class="text-gray-500 mb-6">Tindakan ini tidak dapat dibatalkan. Data surat pernyataan
                                insentif akan dihapus secara permanen.</p>
                            <div class="flex justify-center gap-3">
                                <button wire:click="closeModal"
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">
                                    Batal
                                </button>
                                <button wire:click="delete"
                                    class="px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-colors">
                                    Ya, Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
