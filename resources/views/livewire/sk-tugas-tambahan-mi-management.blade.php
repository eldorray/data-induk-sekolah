<div class="animate-fade-up">
    {{-- Header Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center gap-3 flex-wrap">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari SK atau nama guru..."
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
            </select>
            <select wire:model.live="filterStatus"
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="aktif">Aktif</option>
                <option value="tidak_aktif">Tidak Aktif</option>
            </select>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <button wire:click="openCreateModal"
                class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat SK Tugas Tambahan
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nomor SK</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nama Guru</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Tugas Tambahan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Masa Berlaku</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($skList as $index => $sk)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $skList->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $sk->nomor_sk }}</div>
                                <div class="text-xs text-gray-500">{{ $sk->tanggal_sk?->translatedFormat('d M Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $sk->guru?->full_name_with_title }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $sk->tugas_tambahan }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $sk->berlaku_mulai?->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500">s/d
                                    {{ $sk->berlaku_sampai?->translatedFormat('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($sk->status === 'aktif')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @elseif($sk->status === 'draft')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Draft</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Tidak
                                        Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($sk->status === 'aktif')
                                        <a href="{{ route('sk-tugas-tambahan-mi.print', $sk->id) }}" target="_blank"
                                            class="p-2 rounded-lg hover:bg-blue-50 text-gray-600 hover:text-blue-600 transition-colors"
                                            title="Cetak PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                        </a>
                                    @endif
                                    <button wire:click="openEditModal({{ $sk->id }})"
                                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="openDeleteModal({{ $sk->id }})"
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
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="text-sm">Belum ada SK Tugas Tambahan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($skList->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $skList->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col"
                        @click.away="$wire.closeModal()">
                        {{-- Header --}}
                        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="text-lg font-semibold">
                                {{ $isEditing ? 'Edit SK Tugas Tambahan' : 'Buat SK Tugas Tambahan Baru' }}</h3>
                            <button wire:click="closeModal"
                                class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Body --}}
                        <div class="px-6 py-4 overflow-y-auto flex-1">
                            <form wire:submit="save" class="space-y-4">
                                {{-- Pilih Guru --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Guru <span
                                            class="text-red-500">*</span></label>
                                    @if ($selectedGuru)
                                        <div
                                            class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-200">
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $selectedGuru['nama'] }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $selectedGuru['status'] }}</div>
                                            </div>
                                            <button type="button" wire:click="clearGuru"
                                                class="p-1 hover:bg-gray-200 rounded-lg">
                                                <svg class="w-4 h-4 text-gray-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <div class="relative">
                                            <input type="text" wire:model.live.debounce.300ms="searchGuru"
                                                placeholder="Cari nama guru atau NUPTK..."
                                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                            @if (count($guruResults) > 0)
                                                <div
                                                    class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-48 overflow-y-auto">
                                                    @foreach ($guruResults as $guru)
                                                        <button type="button"
                                                            wire:click="selectGuru({{ $guru['id'] }})"
                                                            class="w-full px-4 py-2 text-left hover:bg-gray-50 border-b border-gray-100 last:border-0">
                                                            <div class="font-medium text-gray-900">{{ $guru['nama'] }}
                                                            </div>
                                                            <div class="text-xs text-gray-500">NUPTK:
                                                                {{ $guru['nuptk'] ?? '-' }}</div>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @error('guru_mi_id')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    {{-- Nomor SK --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor SK <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" wire:model="nomor_sk"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        @error('nomor_sk')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    {{-- Tanggal SK --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal SK <span
                                                class="text-red-500">*</span></label>
                                        <input type="date" wire:model="tanggal_sk"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        @error('tanggal_sk')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    {{-- Tempat Lahir --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat
                                            Lahir</label>
                                        <input type="text" wire:model="tempat_lahir"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    </div>
                                    {{-- Tanggal Lahir --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                            Lahir</label>
                                        <input type="date" wire:model="tanggal_lahir"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    </div>
                                </div>

                                {{-- Pendidikan Terakhir --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan
                                        Terakhir</label>
                                    <select wire:model="pendidikan_terakhir"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        <option value="">Pilih</option>
                                        <option value="SMA/SMK">SMA/SMK</option>
                                        <option value="D1">D1</option>
                                        <option value="D2">D2</option>
                                        <option value="D3">D3</option>
                                        <option value="D4">D4</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                </div>

                                {{-- Tugas Tambahan --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tugas Tambahan <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" wire:model="tugas_tambahan" list="tugas-tambahan-list"
                                        placeholder="Ketik atau pilih dari daftar..."
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    <datalist id="tugas-tambahan-list">
                                        @foreach ($tugasTambahanOptions as $option)
                                            <option value="{{ $option }}">
                                        @endforeach
                                    </datalist>
                                    @error('tugas_tambahan')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    {{-- Berlaku Mulai --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Berlaku Mulai <span
                                                class="text-red-500">*</span></label>
                                        <input type="date" wire:model="berlaku_mulai"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        @error('berlaku_mulai')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    {{-- Berlaku Sampai --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Berlaku Sampai
                                            <span class="text-red-500">*</span></label>
                                        <input type="date" wire:model="berlaku_sampai"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        @error('berlaku_sampai')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="my-4">
                                <h4 class="font-medium text-gray-900">Penandatangan</h4>

                                <div class="grid grid-cols-2 gap-4">
                                    {{-- Nama Penandatangan --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penandatangan
                                            <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model="penandatangan_nama"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        @error('penandatangan_nama')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    {{-- Jabatan Penandatangan --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan
                                            Penandatangan</label>
                                        <input type="text" wire:model="penandatangan_jabatan"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    {{-- Tempat Penetapan --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat
                                            Penetapan</label>
                                        <input type="text" wire:model="tempat_penetapan"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    </div>
                                    {{-- Tanggal Penetapan --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Penetapan
                                            <span class="text-red-500">*</span></label>
                                        <input type="date" wire:model="tanggal_penetapan"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        @error('tanggal_penetapan')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select wire:model="status"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        <option value="draft">Draft</option>
                                        <option value="aktif">Aktif</option>
                                        <option value="tidak_aktif">Tidak Aktif</option>
                                    </select>
                                </div>
                            </form>
                        </div>

                        {{-- Footer --}}
                        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-medium transition-colors">
                                Batal
                            </button>
                            <button type="button" wire:click="save"
                                class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition-colors">
                                {{ $isEditing ? 'Perbarui' : 'Simpan' }}
                            </button>
                        </div>
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
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
                        <div class="text-center">
                            <div
                                class="w-12 h-12 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus SK Tugas Tambahan</h3>
                            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus SK ini? Tindakan ini tidak
                                dapat dibatalkan.</p>
                            <div class="flex gap-3 justify-center">
                                <button wire:click="closeModal"
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-medium transition-colors">
                                    Batal
                                </button>
                                <button wire:click="delete"
                                    class="px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-colors">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
