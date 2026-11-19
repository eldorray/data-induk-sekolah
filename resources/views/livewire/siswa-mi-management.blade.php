<div class="animate-fade-up">
    {{-- Header Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari siswa"
                    class="pl-9 pr-3 py-2 rounded-lg border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent w-64 text-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <select wire:model.live="perPage"
                class="px-3 py-2 rounded-lg border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="openDeleteAllModal"
                class="px-3 py-2 rounded-lg border border-red-200 bg-white hover:bg-red-50 text-red-600 text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg>
                Hapus semua
            </button>
            <button wire:click="openGenerateModal"
                class="px-3 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4 9h16M4 15h16M10 3 8 21M16 3l-2 18" />
                </svg>
                Nomor induk
            </button>
            <button wire:click="downloadTemplate"
                class="px-3 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Template
            </button>
            <button wire:click="openImportModal"
                class="px-3 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Import
            </button>
            <button wire:click="export"
                class="px-3 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Export
            </button>
            <button wire:click="openCreateModal"
                class="px-3 py-2 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah siswa
            </button>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-4 p-4 rounded-lg bg-madrasah-50 border border-madrasah-200 text-madrasah-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('warning'))
        <div class="mb-4 p-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-800">
            {{ session('warning') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-600">No
                        </th>
                        <th wire:click="sortBy('nama_lengkap')"
                            class="px-4 py-2.5 text-left text-xs font-medium text-gray-600 cursor-pointer hover:text-gray-900">
                            <div class="flex items-center gap-1">
                                Nama lengkap
                                @if ($sortField === 'nama_lengkap')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('nisn')"
                            class="px-4 py-2.5 text-left text-xs font-medium text-gray-600 cursor-pointer hover:text-gray-900">
                            <div class="flex items-center gap-1">
                                NISN
                                @if ($sortField === 'nisn')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('nomor_induk')"
                            class="px-4 py-2.5 text-left text-xs font-medium text-gray-600 cursor-pointer hover:text-gray-900">
                            <div class="flex items-center gap-1">
                                No. induk
                                @if ($sortField === 'nomor_induk')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-600">
                            Tingkat - rombel</th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-600">
                            Jenis kelamin</th>
                        <th wire:click="sortBy('status')"
                            class="px-4 py-2.5 text-left text-xs font-medium text-gray-600 cursor-pointer hover:text-gray-900">
                            <div class="flex items-center gap-1">
                                Status
                                @if ($sortField === 'status')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M5 15l7-7 7 7"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-600">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($siswas as $index => $siswa)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $siswas->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $siswa->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500 font-mono tabular-nums">NIK: {{ $siswa->nik ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 font-mono text-[13px] tabular-nums">{{ $siswa->nisn ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 font-mono text-[13px] tabular-nums">{{ $siswa->nomor_induk_lengkap ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $siswa->tingkat_rombel ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                @if ($siswa->jenis_kelamin === 'L')
                                    <span
                                        class="text-gray-700">Laki-laki</span>
                                @elseif($siswa->jenis_kelamin === 'P')
                                    <span
                                        class="text-gray-700">Perempuan</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if ($siswa->status === 'Aktif')
                                    <span
                                        class="inline-flex items-center gap-1.5 text-[13px] text-gray-700 before:h-1.5 before:w-1.5 before:rounded-full before:bg-madrasah-600">{{ $siswa->status }}</span>
                                @elseif($siswa->status === 'Lulus')
                                    <span
                                        class="inline-flex items-center gap-1.5 text-[13px] text-gray-700 before:h-1.5 before:w-1.5 before:rounded-full before:bg-madrasah-600">{{ $siswa->status }}</span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 text-[13px] text-gray-700 before:h-1.5 before:w-1.5 before:rounded-full before:bg-gray-400">{{ $siswa->status }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openEditModal({{ $siswa->id }})"
                                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="openDeleteModal({{ $siswa->id }})"
                                        class="p-2 rounded-lg hover:bg-red-50 text-gray-600 hover:text-red-600 transition-colors"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                                <p class="text-sm">Belum ada data siswa</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($siswas->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $siswas->links() }}
            </div>
        @endif
    </div>

    {{-- Generate nomor induk --}}
    @if ($showGenerateModal)
        @include('livewire.partials.generate-nomor-induk-modal', [
            'kelasList' => $this->kelasNomorInduk(),
            'pratinjau' => $this->pratinjauNomorInduk(),
        ])
    @endif

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-gray-900/40" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div
                        class="relative w-full max-w-3xl bg-white rounded-xl shadow-float max-h-[90vh] overflow-y-auto">
                        <form wire:submit="save">
                            <div class="bg-white px-6 py-5 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ $isEditing ? 'Edit data siswa' : 'Tambah data siswa' }}
                                </h3>
                            </div>
                            <div class="bg-white px-6 py-6 max-h-[70vh] overflow-y-auto">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Nama Lengkap --}}
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama lengkap <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" wire:model="nama_lengkap"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('nama_lengkap')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- NISN --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">NISN</label>
                                        <input type="text" wire:model="nisn"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('nisn')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Nomor induk --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor induk</label>
                                        <input type="text" wire:model="nomor_induk" inputmode="numeric" maxlength="6"
                                            placeholder="Contoh: 240001"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent font-mono tabular-nums">
                                        <p class="mt-1 text-xs text-gray-500">2 digit tahun masuk + 4 digit urut. Kosongkan untuk diisi lewat generate</p>
                                        @error('nomor_induk')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- NIK --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                                        <input type="text" wire:model="nik"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('nik')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Tempat Lahir --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat
                                            Lahir</label>
                                        <input type="text" wire:model="tempat_lahir"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('tempat_lahir')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Tanggal Lahir --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                            Lahir</label>
                                        <input type="date" wire:model="tanggal_lahir"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('tanggal_lahir')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Tingkat Rombel --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat -
                                            Rombel</label>
                                        <input type="text" wire:model="tingkat_rombel"
                                            placeholder="Contoh: Kelas 1 - KELAS 1A"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('tingkat_rombel')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Status --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select wire:model="status"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                            <option value="Aktif">Aktif</option>
                                            <option value="Tidak Aktif">Tidak Aktif</option>
                                            <option value="Lulus">Lulus</option>
                                            <option value="Pindah">Pindah</option>
                                            <option value="Keluar">Keluar</option>
                                        </select>
                                        @error('status')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Jenis Kelamin --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis
                                            Kelamin</label>
                                        <select wire:model="jenis_kelamin"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                            <option value="">Pilih jenis kelamin</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- No Telepon --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">No telepon</label>
                                        <input type="text" wire:model="no_telepon"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('no_telepon')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Alamat --}}
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                        <textarea wire:model="alamat" rows="2"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent"></textarea>
                                        @error('alamat')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Kebutuhan Khusus --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kebutuhan
                                            Khusus</label>
                                        <input type="text" wire:model="kebutuhan_khusus"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('kebutuhan_khusus')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Disabilitas --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Disabilitas</label>
                                        <input type="text" wire:model="disabilitas"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('disabilitas')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Nomor KIP/PIP --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor
                                            KIP/PIP</label>
                                        <input type="text" wire:model="nomor_kip_pip"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('nomor_kip_pip')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2 border-t border-gray-200 pt-4 mt-2">
                                        <h4 class="text-sm font-medium text-gray-700 mb-3">Data orang tua / wali</h4>
                                    </div>

                                    {{-- Nama Ayah Kandung --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama ayah
                                            Kandung</label>
                                        <input type="text" wire:model="nama_ayah_kandung"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('nama_ayah_kandung')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Nama Ibu Kandung --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama ibu
                                            Kandung</label>
                                        <input type="text" wire:model="nama_ibu_kandung"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('nama_ibu_kandung')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Nama Wali --}}
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama wali</label>
                                        <input type="text" wire:model="nama_wali"
                                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('nama_wali')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                                <button type="button" wire:click="closeModal"
                                    class="px-3 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-3 py-2 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition-colors">
                                    {{ $isEditing ? 'Simpan perubahan' : 'Tambah siswa' }}
                                </button>
                            </div>
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
            <div class="fixed inset-0 bg-gray-900/40" wire:click="closeModal"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div
                    class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-float sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    <div class="bg-white px-6 py-6">
                        <div class="flex items-center justify-center w-10 h-10 mx-auto bg-red-100 rounded-full mb-4">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 text-center mb-2">Hapus data siswa</h3>
                        <p class="text-sm text-gray-600 text-center">Apakah Anda yakin ingin menghapus data siswa ini?
                            Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                        <button type="button" wire:click="closeModal"
                            class="px-3 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">
                            Batal
                        </button>
                        <button type="button" wire:click="delete"
                            class="px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-colors">
                            Hapus
                        </button>
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
            <div class="fixed inset-0 bg-gray-900/40" wire:click="closeModal"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white rounded-xl shadow-float p-6">
                    <div class="flex items-center justify-center w-10 h-10 mx-auto bg-red-100 rounded-full mb-4">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 text-center mb-2">Hapus semua data siswa</h3>
                    <p class="text-sm text-gray-600 text-center mb-4">
                        Anda akan menghapus <strong>SEMUA</strong> data siswa. Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="p-3 bg-red-50 rounded-lg border border-red-200 mb-4">
                        <p class="text-sm text-red-700 text-center">
                            Pastikan Anda sudah melakukan backup data sebelum melanjutkan.
                        </p>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button wire:click="closeModal"
                            class="px-3 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">Batal</button>
                        <button wire:click="deleteAll"
                            class="px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-colors">Hapus
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
            <div class="fixed inset-0 bg-gray-900/40" wire:click="closeImportModal"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="relative w-full max-w-lg bg-white rounded-xl shadow-float">
                    <form wire:submit="import">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900">Import data siswa</h3>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <p class="text-sm text-blue-800 mb-2">Petunjuk import:</p>
                                <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                                    <li>Download template Excel terlebih dahulu</li>
                                    <li>Isi data sesuai format kolom yang tersedia</li>
                                    <li>Kolom wajib: nama_lengkap</li>
                                    <li>NISN dan NIK harus unik</li>
                                </ul>
                            </div>

                            <div>
                                <button type="button" wire:click="downloadTemplate"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium flex items-center justify-center gap-2 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                        </path>
                                    </svg>
                                    Download template
                                </button>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">File Excel</label>
                                <input type="file" wire:model="importFile" accept=".xlsx,.xls,.csv"
                                    class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                @error('importFile')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            @if ($importFile)
                                <div class="text-sm text-madrasah-600">
                                    File terpilih: {{ $importFile->getClientOriginalName() }}
                                </div>
                            @endif

                            <div wire:loading wire:target="importFile">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
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
                                <div class="p-4 bg-red-50 rounded-lg border border-red-200">
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
                                class="px-3 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">Batal</button>
                            <button type="submit"
                                class="px-3 py-2 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition-colors"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="import">Import data</span>
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
