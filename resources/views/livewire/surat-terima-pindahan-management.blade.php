<div class="animate-fade-up">
    {{-- Header Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari surat..."
                    class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent w-64 text-sm">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <select wire:model.live="filterStatus"
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="disetujui">Disetujui</option>
                <option value="dibatalkan">Dibatalkan</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="openCetakModal"
                class="px-4 py-2.5 rounded-xl border border-gray-900 text-gray-900 hover:bg-gray-900 hover:text-white text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Cetak Dokumen
            </button>
            <button wire:click="openCreateModal"
                class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Surat Menerima Siswa Pindahan
            </button>
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

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nomor Surat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Asal Sekolah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Surat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($surats as $index => $surat)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $surats->firstItem() + $index }}</td>
                            <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $surat->nomor_surat }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $surat->nama_siswa }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $surat->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $surat->asal_sekolah ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $surat->kelas ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if ($surat->status === 'draft')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Draft</span>
                                @elseif($surat->status === 'disetujui')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    @if ($surat->status === 'disetujui')
                                        <a href="{{ route('surat-terima-pindahan.print', $surat->id) }}" target="_blank"
                                            class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors"
                                            title="Cetak PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                        </a>
                                    @endif
                                    <button wire:click="openEditModal({{ $surat->id }})"
                                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="openDeleteModal({{ $surat->id }})"
                                        class="p-2 rounded-lg hover:bg-red-50 text-gray-600 hover:text-red-600 transition-colors"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="text-sm">Belum ada data surat menerima siswa pindahan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($surats->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $surats->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
                        <form wire:submit="save">
                            <div class="border-b border-gray-200 px-6 py-4 sticky top-0 bg-white z-10">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $isEditing ? 'Edit Surat Menerima Siswa Pindahan' : 'Buat Surat Menerima Siswa Pindahan' }}
                                </h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                {{-- Data Siswa --}}
                                <div class="border-b border-gray-200 pb-2 mb-2">
                                    <h4 class="text-sm font-semibold text-gray-900">Data Siswa Pindahan</h4>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Siswa <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="nama_siswa" placeholder="Nama lengkap siswa"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    @error('nama_siswa')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                                        <input type="text" wire:model="tempat_lahir" placeholder="Contoh: Jombang"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        @error('tempat_lahir')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                        <input type="date" wire:model="tanggal_lahir"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        @error('tanggal_lahir')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                                        <input type="text" wire:model="kelas" placeholder="Contoh: V (Lima)"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        @error('kelas')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                                        <select wire:model="jenis_kelamin"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Asal Sekolah</label>
                                    <input type="text" wire:model="asal_sekolah" placeholder="Contoh: SDN Margorejo V/407"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    @error('asal_sekolah')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Orang Tua</label>
                                    <input type="text" wire:model="nama_orang_tua" placeholder="Nama ayah/ibu/wali"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    @error('nama_orang_tua')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Rumah</label>
                                    <textarea wire:model="alamat_rumah" rows="2" placeholder="Alamat lengkap"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm"></textarea>
                                    @error('alamat_rumah')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Data Surat --}}
                                <div class="border-b border-gray-200 pb-2 mb-2 pt-2">
                                    <h4 class="text-sm font-semibold text-gray-900">Data Surat</h4>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model="nomor_surat"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        @error('nomor_surat')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat <span class="text-red-500">*</span></label>
                                        <input type="date" wire:model="tanggal_surat"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        @error('tanggal_surat')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                    <select wire:model="status"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        <option value="draft">Draft</option>
                                        <option value="disetujui">Disetujui</option>
                                        <option value="dibatalkan">Dibatalkan</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="border-t border-gray-200 px-6 py-4 flex justify-end gap-3 sticky bottom-0 bg-white">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition-colors">
                                    {{ $isEditing ? 'Simpan Perubahan' : 'Simpan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
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
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus Surat Menerima Siswa Pindahan?</h3>
                            <p class="text-gray-500 mb-6">Tindakan ini tidak dapat dibatalkan. Data surat akan dihapus secara permanen.</p>
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

    {{-- Cetak Dokumen Modal --}}
    @if ($showCetakModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto" x-data="{ openPdf(url) { window.open(url, '_blank'); } }"
                @open-cetak-pdf.window="openPdf($event.detail.url)">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
                        <form wire:submit="cetakDokumen">
                            <div class="border-b border-gray-200 px-6 py-4 sticky top-0 bg-white z-10 flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Cetak Dokumen</h3>
                                <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <p class="text-sm text-gray-600">
                                    Pilih surat yang sudah disetujui untuk dicetak menjadi satu file PDF.
                                </p>

                                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer">
                                    <input type="checkbox" wire:model.live="selectAll"
                                        class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-gray-900">
                                    <span class="text-sm font-medium text-gray-900">Pilih Semua ({{ count($disetujuiSurats) }} surat)</span>
                                </label>

                                <div class="border border-gray-200 rounded-xl overflow-hidden">
                                    @forelse($disetujuiSurats as $item)
                                        <label class="flex items-start gap-3 px-4 py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 cursor-pointer">
                                            <input type="checkbox" wire:model="selectedIds" value="{{ $item->id }}"
                                                class="mt-1 w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-gray-900">
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->nama_siswa }}</div>
                                                <div class="text-xs text-gray-500 mt-0.5">
                                                    {{ $item->nomor_surat }} &middot; {{ $item->asal_sekolah ?? '-' }} &middot; {{ $item->tanggal_surat->format('d/m/Y') }}
                                                </div>
                                            </div>
                                        </label>
                                    @empty
                                        <div class="px-4 py-8 text-center text-sm text-gray-500">
                                            Belum ada surat dengan status <strong>Disetujui</strong>.
                                        </div>
                                    @endforelse
                                </div>

                                <div class="text-xs text-gray-500">
                                    <span class="font-medium">{{ count($selectedIds) }}</span> surat dipilih
                                </div>
                            </div>
                            <div class="border-t border-gray-200 px-6 py-4 flex justify-end gap-3 sticky bottom-0 bg-white">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">
                                    Batal
                                </button>
                                <button type="submit" @disabled(count($selectedIds) === 0)
                                    class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                    Cetak {{ count($selectedIds) }} Dokumen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
