<div class="animate-fade-up">
    {{-- Header Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center gap-3 flex-wrap">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari guru..."
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
            <select wire:model.live="filterStatus"
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Tidak Aktif</option>
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
                Tambah Guru
            </button>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-gray-900"
                            wire:click="sortBy('full_name')">
                            Nama
                            @if ($sortField === 'full_name')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            NIK/NIP</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            NUPTK</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aktif</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($gurus as $index => $guru)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $gurus->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $guru->full_name_with_title }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $guru->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-mono">{{ $guru->nik }}</div>
                                @if ($guru->nip)
                                    <div class="text-xs text-gray-500">NIP: {{ $guru->nip }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $guru->nuptk ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if ($guru->status_pegawai === 'PNS')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">PNS</span>
                                @elseif($guru->status_pegawai === 'GTY')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">GTY</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">GTT</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($guru->is_active)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Tidak
                                        Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openEditModal({{ $guru->id }})"
                                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="openDeleteModal({{ $guru->id }})"
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
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                                    </path>
                                </svg>
                                <p class="text-sm">Belum ada data guru</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($gurus->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $gurus->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div
                        class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
                        <form wire:submit="save">
                            <div class="border-b border-gray-200 px-6 py-4 sticky top-0 bg-white z-10">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $isEditing ? 'Edit Data Guru' : 'Tambah Data Guru' }}
                                </h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                {{-- Identitas Utama --}}
                                <div class="border-b border-gray-200 pb-3">
                                    <h4 class="text-sm font-semibold text-gray-700">Identitas Utama</h4>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" wire:model="nik" maxlength="16"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent font-mono">
                                        @error('nik')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                                        <input type="text" wire:model="nip"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent font-mono">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">NUPTK</label>
                                        <input type="text" wire:model="nuptk"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent font-mono">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">NPK
                                            (Kemenag)</label>
                                        <input type="text" wire:model="npk"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent font-mono">
                                    </div>
                                </div>

                                {{-- Nama & Gelar --}}
                                <div class="border-b border-gray-200 pb-3 pt-2">
                                    <h4 class="text-sm font-semibold text-gray-700">Nama & Gelar</h4>
                                </div>
                                <div class="grid grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Gelar Depan</label>
                                        <input type="text" wire:model="front_title" placeholder="Drs."
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" wire:model="full_name"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('full_name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Gelar
                                            Belakang</label>
                                        <input type="text" wire:model="back_title" placeholder="S.Pd."
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                    </div>
                                </div>

                                {{-- Biodata --}}
                                <div class="border-b border-gray-200 pb-3 pt-2">
                                    <h4 class="text-sm font-semibold text-gray-700">Biodata</h4>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span
                                                class="text-red-500">*</span></label>
                                        <select wire:model="gender"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat
                                            Lahir</label>
                                        <input type="text" wire:model="pob"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                            Lahir</label>
                                        <input type="date" wire:model="dob"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                                        <input type="text" wire:model="phone_number"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status
                                            Pegawai</label>
                                        <select wire:model="status_pegawai"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                            <option value="PNS">PNS</option>
                                            <option value="GTY">Guru Tetap Yayasan (GTY)</option>
                                            <option value="GTT">Guru Tidak Tetap (GTT)</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                    <textarea wire:model="address" rows="2"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent"></textarea>
                                </div>
                                <div>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model="is_active"
                                            class="rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                                        <span class="text-sm font-medium text-gray-700">Guru Aktif</span>
                                    </label>
                                </div>

                                {{-- File Upload SK --}}
                                <div class="border-b border-gray-200 pb-3 pt-2">
                                    <h4 class="text-sm font-semibold text-gray-700">Dokumen SK</h4>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">SK Awal
                                            (Pengangkatan)</label>
                                        @if ($current_sk_awal)
                                            <div
                                                class="mb-2 p-2 bg-gray-50 rounded-lg flex items-center justify-between">
                                                <a href="{{ asset('storage/' . $current_sk_awal) }}" target="_blank"
                                                    class="text-sm text-blue-600 hover:underline">Lihat File</a>
                                                <button type="button" wire:click="deleteSKAwal"
                                                    class="text-red-500 text-sm">Hapus</button>
                                            </div>
                                        @endif
                                        <input type="file" wire:model="sk_awal" accept=".pdf,.jpg,.jpeg,.png"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        <p class="text-xs text-gray-500 mt-1">PDF/JPG/PNG, maks 2MB</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">SK Akhir
                                            (Terakhir)</label>
                                        @if ($current_sk_akhir)
                                            <div
                                                class="mb-2 p-2 bg-gray-50 rounded-lg flex items-center justify-between">
                                                <a href="{{ asset('storage/' . $current_sk_akhir) }}" target="_blank"
                                                    class="text-sm text-blue-600 hover:underline">Lihat File</a>
                                                <button type="button" wire:click="deleteSKAkhir"
                                                    class="text-red-500 text-sm">Hapus</button>
                                            </div>
                                        @endif
                                        <input type="file" wire:model="sk_akhir" accept=".pdf,.jpg,.jpeg,.png"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        <p class="text-xs text-gray-500 mt-1">PDF/JPG/PNG, maks 2MB</p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end gap-3 sticky bottom-0">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition-colors">
                                    {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Guru' }}
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
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Hapus Data Guru</h3>
                        <p class="text-sm text-gray-600 text-center mb-4">Yakin ingin menghapus data guru ini? Tindakan
                            ini tidak dapat dibatalkan.</p>
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

    {{-- Delete All Confirmation Modal --}}
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
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Hapus Semua Data Guru</h3>
                        <p class="text-sm text-gray-600 text-center mb-4">
                            Anda akan menghapus <strong>SEMUA</strong> data guru. Tindakan ini tidak dapat dibatalkan!
                        </p>
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

    {{-- Import Modal --}}
    @if ($showImportModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeImportModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl">
                        <form wire:submit="import">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-semibold text-gray-900">Import Data Guru</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <button type="button" wire:click="downloadTemplate"
                                    class="w-full px-4 py-3 rounded-xl border-2 border-dashed border-gray-300 hover:border-gray-400 text-gray-700 text-sm font-medium flex items-center justify-center gap-2 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download Template Excel
                                </button>
                                <div class="text-sm text-gray-600">
                                    <p class="font-medium mb-2">Petunjuk Import:</p>
                                    <ul class="list-disc list-inside space-y-1 text-gray-500">
                                        <li>Download template Excel terlebih dahulu</li>
                                        <li>Isi data sesuai format kolom yang tersedia</li>
                                        <li>NIK harus unik (16 digit)</li>
                                        <li>Jenis kelamin: L atau P</li>
                                        <li>Status: PNS, GTY, atau GTT</li>
                                    </ul>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File
                                        Excel</label>
                                    <input type="file" wire:model="importFile" accept=".xlsx,.xls,.csv"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                    <div wire:loading wire:target="importFile"
                                        class="mt-2 flex items-center gap-2 text-sm text-gray-500">
                                        <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Mengupload file...
                                    </div>
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
                                    wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="import">Import Data</span>
                                    <span wire:loading wire:target="import">Mengimport...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
