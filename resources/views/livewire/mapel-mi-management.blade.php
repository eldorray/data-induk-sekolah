<div class="animate-fade-up">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center gap-3 flex-wrap">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari mata pelajaran..."
                    class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent w-64 text-sm">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <select wire:model.live="perPage"
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <button wire:click="downloadTemplate"
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Template
            </button>
            <button wire:click="openDeleteAllModal"
                class="px-4 py-2.5 rounded-xl border border-red-200 bg-red-50 hover:bg-red-100 text-red-700 text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
                Hapus Semua
            </button>
            <button wire:click="openImportModal"
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Import
            </button>
            <button wire:click="export"
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Export
            </button>
            <button wire:click="openCreateModal"
                class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Mapel
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">{{ session('success') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="mb-4 p-4 rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-800">{{ session('warning') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-gray-900"
                            wire:click="sortBy('kode_mapel')">Kode @if ($sortField === 'kode_mapel')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-gray-900"
                            wire:click="sortBy('nama_mapel')">Nama Mapel @if ($sortField === 'nama_mapel')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Kelompok</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Jurusan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Jam/Minggu</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody wire:sort="sortItem" class="divide-y divide-gray-100">
                    @forelse($mapels as $index => $mapel)
                        <tr wire:sort:item="{{ $mapel->id }}" wire:key="mapel-{{ $mapel->id }}"
                            class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <div wire:sort:handle
                                        class="cursor-grab active:cursor-grabbing p-1 rounded hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 8h16M4 16h16"></path>
                                        </svg>
                                    </div>
                                    <span>{{ $mapels->firstItem() + $index }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $mapel->kode_mapel ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $mapel->nama_mapel }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($mapel->kelompok === 'PAI')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">PAI</span>
                                @else<span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Umum</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $mapel->jurusan ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $mapel->jam_per_minggu }} jam</td>
                            <td class="px-6 py-4 text-sm">
                                @if ($mapel->is_active)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @else<span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Tidak
                                        Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openEditModal({{ $mapel->id }})"
                                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="openDeleteModal({{ $mapel->id }})"
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
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500"><svg
                                    class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                                <p class="text-sm">Belum ada data mata pelajaran</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($mapels->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">{{ $mapels->links() }}</div>
        @endif
    </div>

    @if ($showModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl">
                        <form wire:submit="save">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $isEditing ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran' }}</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Kode
                                            Mapel</label><input type="text" wire:model="kode_mapel" maxlength="20"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent font-mono">
                                        @error('kode_mapel')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div><label
                                            class="block text-sm font-medium text-gray-700 mb-1">Jam/Minggu</label><input
                                            type="number" wire:model="jam_per_minggu" min="1" max="20"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                    </div>
                                </div>
                                <div><label class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Pelajaran
                                        <span class="text-red-500">*</span></label><input type="text"
                                        wire:model="nama_mapel"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                    @error('nama_mapel')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label
                                            class="block text-sm font-medium text-gray-700 mb-1">Kelompok</label><select
                                            wire:model="kelompok"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                            <option value="PAI">PAI</option>
                                            <option value="Umum">Umum</option>
                                        </select></div>
                                    <div><label
                                            class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label><input
                                            type="text" wire:model="jurusan"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                    </div>
                                </div>
                                <div><label class="flex items-center gap-2 cursor-pointer"><input type="checkbox"
                                            wire:model="is_active"
                                            class="rounded border-gray-300 text-gray-900 focus:ring-gray-900"><span
                                            class="text-sm font-medium text-gray-700">Aktif</span></label></div>
                            </div>
                            <div
                                class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition-colors">{{ $isEditing ? 'Simpan Perubahan' : 'Tambah Mapel' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endif

    @if ($showDeleteModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg></div>
                        <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Hapus Mata Pelajaran</h3>
                        <p class="text-sm text-gray-600 text-center mb-4">Yakin ingin menghapus mata pelajaran ini?
                            Tindakan ini tidak dapat dibatalkan.</p>
                        <div class="flex justify-end gap-3">
                            <button wire:click="closeModal"
                                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">Batal</button>
                            <button wire:click="delete"
                                class="px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-colors">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    @if ($showDeleteAllModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg></div>
                        <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Hapus Semua Data Mapel</h3>
                        <p class="text-sm text-gray-600 text-center mb-4">Anda akan menghapus <strong>SEMUA</strong>
                            data mata pelajaran. Tindakan ini tidak dapat dibatalkan!</p>
                        <div class="p-3 bg-red-50 rounded-xl border border-red-200 mb-4">
                            <p class="text-sm text-red-700 text-center">⚠️ Pastikan Anda sudah melakukan backup data
                                sebelum melanjutkan.</p>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button wire:click="closeModal"
                                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">Batal</button>
                            <button wire:click="deleteAll"
                                class="px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-colors">Hapus
                                Semua</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    @if ($showImportModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeImportModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl">
                        <form wire:submit="import">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-semibold text-gray-900">Import Data Mata Pelajaran</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <button type="button" wire:click="downloadTemplate"
                                    class="w-full px-4 py-3 rounded-xl border-2 border-dashed border-gray-300 hover:border-gray-400 text-gray-700 text-sm font-medium flex items-center justify-center gap-2 transition-colors"><svg
                                        class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>Download Template Excel</button>
                                <div class="text-sm text-gray-600">
                                    <p class="font-medium mb-2">Petunjuk Import:</p>
                                    <ul class="list-disc list-inside space-y-1 text-gray-500">
                                        <li>Download template Excel terlebih dahulu</li>
                                        <li>Isi data sesuai format kolom yang tersedia</li>
                                        <li>Kode mapel harus unik</li>
                                        <li>Kelompok: PAI atau Umum</li>
                                    </ul>
                                </div>
                                <div><label class="block text-sm font-medium text-gray-700 mb-2">Pilih File
                                        Excel</label><input type="file" wire:model="importFile"
                                        accept=".xlsx,.xls,.csv"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                    <div wire:loading wire:target="importFile"
                                        class="mt-2 flex items-center gap-2 text-sm text-gray-500"><svg
                                            class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>Mengupload file...</div>
                                </div>
                                @if (count($importErrors) > 0)
                                    <div class="p-4 bg-red-50 rounded-xl border border-red-200">
                                        <p class="text-sm font-medium text-red-800 mb-2">Terjadi kesalahan:</p>
                                        <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                                            @foreach ($importErrors as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            <div
                                class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                                <button type="button" wire:click="closeImportModal"
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition-colors"
                                    wire:loading.attr="disabled"><span wire:loading.remove wire:target="import">Import
                                        Data</span><span wire:loading
                                        wire:target="import">Mengimport...</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
