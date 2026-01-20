<div class="animate-fade-up">
    {{-- Header Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari mutasi..."
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
        <button wire:click="openCreateModal"
            class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Buat Surat Mutasi
        </button>
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
                            Nomor Surat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nama Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Tanggal Mutasi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Jenis</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($mutasis as $index => $mutasi)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $mutasis->firstItem() + $index }}</td>
                            <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $mutasi->nomor_surat }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $mutasi->siswa->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">NISN: {{ $mutasi->siswa->nisn ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $mutasi->tanggal_mutasi->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($mutasi->jenis_mutasi === 'pindah')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Pindah</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Keluar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($mutasi->status === 'draft')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Draft</span>
                                @elseif($mutasi->status === 'disetujui')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($mutasi->status === 'disetujui')
                                        <a href="{{ route('mutasi.print', $mutasi->id) }}" target="_blank"
                                            class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors"
                                            title="Print PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                        </a>
                                    @endif
                                    <button wire:click="openEditModal({{ $mutasi->id }})"
                                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="openDeleteModal({{ $mutasi->id }})"
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
                                <p class="text-sm">Belum ada data mutasi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($mutasis->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $mutasis->links() }}
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
                        class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
                        <form wire:submit="save">
                            <div class="border-b border-gray-200 px-6 py-4 sticky top-0 bg-white z-10">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $isEditing ? 'Edit Surat Mutasi' : 'Buat Surat Mutasi' }}
                                </h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                {{-- Pilih Siswa --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Siswa <span
                                            class="text-red-500">*</span></label>
                                    @if ($selectedSiswa)
                                        <div
                                            class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-200">
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $selectedSiswa['nama'] }}
                                                </div>
                                                <div class="text-sm text-gray-500">NISN:
                                                    {{ $selectedSiswa['nisn'] ?? '-' }} |
                                                    {{ $selectedSiswa['kelas'] ?? '-' }}</div>
                                            </div>
                                            <button type="button" wire:click="clearSiswa"
                                                class="text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <div class="relative">
                                            <input type="text" wire:model.live.debounce.300ms="searchSiswa"
                                                placeholder="Cari nama/NISN siswa..."
                                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                            @if (count($siswaResults) > 0)
                                                <div
                                                    class="absolute z-20 w-full mt-1 bg-white rounded-xl border border-gray-200 shadow-lg max-h-60 overflow-y-auto">
                                                    @foreach ($siswaResults as $siswa)
                                                        <button type="button"
                                                            wire:click="selectSiswa({{ $siswa['id'] }})"
                                                            class="w-full px-4 py-3 text-left hover:bg-gray-50 border-b border-gray-100 last:border-b-0">
                                                            <div class="font-medium text-gray-900">
                                                                {{ $siswa['nama'] }}
                                                            </div>
                                                            <div class="text-sm text-gray-500">NISN:
                                                                {{ $siswa['nisn'] ?? '-' }} |
                                                                {{ $siswa['kelas'] ?? '-' }}
                                                            </div>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @error('siswa_id')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    {{-- Nomor Surat --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" wire:model="nomor_surat"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('nomor_surat')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Jenis Mutasi --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Mutasi <span
                                                class="text-red-500">*</span></label>
                                        <select wire:model="jenis_mutasi"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                            <option value="pindah">Pindah Sekolah</option>
                                            <option value="keluar">Keluar/Berhenti</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    {{-- Tanggal Surat --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat <span
                                                class="text-red-500">*</span></label>
                                        <input type="date" wire:model="tanggal_surat"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('tanggal_surat')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Tanggal Mutasi --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mutasi
                                            <span class="text-red-500">*</span></label>
                                        <input type="date" wire:model="tanggal_mutasi"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                        @error('tanggal_mutasi')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Alasan Mutasi --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Mutasi <span
                                            class="text-red-500">*</span></label>
                                    <textarea wire:model="alasan_mutasi" rows="2"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                                        placeholder="Contoh: Ikut pindah orang tua"></textarea>
                                    @error('alasan_mutasi')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="border-t border-gray-200 pt-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Data Sekolah Tujuan (Opsional)
                                    </h4>
                                </div>

                                {{-- Sekolah Tujuan --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sekolah
                                        Tujuan</label>
                                    <input type="text" wire:model="sekolah_tujuan"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    {{-- NPSN Tujuan --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">NPSN Sekolah
                                            Tujuan</label>
                                        <input type="text" wire:model="npsn_tujuan"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                    </div>

                                    {{-- Status --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select wire:model="status"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                                            <option value="draft">Draft</option>
                                            <option value="disetujui">Disetujui</option>
                                            <option value="dibatalkan">Dibatalkan</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Alamat Tujuan --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Sekolah
                                        Tujuan</label>
                                    <textarea wire:model="alamat_tujuan" rows="2"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent"></textarea>
                                </div>
                            </div>
                            <div
                                class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end gap-3 sticky bottom-0">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition-colors">
                                    {{ $isEditing ? 'Simpan Perubahan' : 'Buat Surat' }}
                                </button>
                            </div>
                        </form>
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
                <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Hapus Surat Mutasi</h3>
                    <p class="text-sm text-gray-600 text-center mb-4">Yakin ingin menghapus surat mutasi ini?</p>
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
</div>
