<div>
    {{-- Header Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari tahun ajaran"
                    class="pl-9 pr-3 py-2 rounded-lg border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent w-64 text-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        <button wire:click="openCreateModal"
            class="px-3 py-2 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah tahun ajaran
        </button>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-4 p-4 rounded-lg bg-madrasah-50 border border-madrasah-200 text-madrasah-800">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- Info Card --}}
    <div class="mb-6 p-4 rounded-lg bg-blue-50 border border-blue-200 text-blue-800 text-sm">
        <div class="flex items-start gap-3">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                Kelola nilai ijazah siswa kelas 6 berdasarkan tahun ajaran. Klik <strong>Detail</strong>
                untuk menginput nilai rata-rata raport, nilai UM, dan mencetak hasil.
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-600">No</th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-600">Tahun ajaran</th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-600">Jumlah siswa</th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-600">Status</th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-600">Keterangan</th>
                        <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tahunAjarans as $index => $tahun)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $tahunAjarans->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('nilai-ijazah.show', $tahun->id) }}" wire:navigate
                                    class="text-sm font-medium text-gray-900 hover:text-blue-700 hover:underline">
                                    {{ $tahun->nama_tahun_ajaran }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $tahun->jumlah_siswa_count ?? 0 }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if ($tahun->status)
                                    <span class="inline-flex items-center gap-1.5 text-[13px] text-gray-700 before:h-1.5 before:w-1.5 before:rounded-full before:bg-madrasah-600">Aktif</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-[13px] text-gray-700 before:h-1.5 before:w-1.5 before:rounded-full before:bg-gray-400">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $tahun->keterangan ?: '-' }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('nilai-ijazah.show', $tahun->id) }}" wire:navigate
                                        class="px-3 py-1.5 rounded-lg bg-gray-900 text-white text-xs font-medium hover:bg-gray-800 transition-colors">
                                        Detail
                                    </a>
                                    <button wire:click="openEditModal({{ $tahun->id }})"
                                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="openDeleteModal({{ $tahun->id }})"
                                        class="p-2 rounded-lg hover:bg-red-50 text-gray-600 hover:text-red-600 transition-colors"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                <p class="text-sm">Belum ada tahun ajaran. Klik "Tambah Tahun Ajaran" untuk memulai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tahunAjarans->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $tahunAjarans->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-gray-900/40" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-xl shadow-float">
                        <form wire:submit="save">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ $isEditing ? 'Edit tahun ajaran' : 'Tambah tahun ajaran' }}
                                </h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama tahun ajaran <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="nama_tahun_ajaran" placeholder="contoh: 2025/2026"
                                        class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    @error('nama_tahun_ajaran')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" wire:model="status" value="1"
                                            class="w-4 h-4 rounded text-gray-900 border-gray-300 focus:ring-gray-900">
                                        <span class="text-sm text-gray-700">Aktif</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                                    <textarea wire:model="keterangan" rows="2" placeholder="Opsional"
                                        class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm resize-none"></textarea>
                                    @error('keterangan')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="border-t border-gray-200 px-6 py-4 flex justify-end gap-2">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium">
                                    {{ $isEditing ? 'Simpan perubahan' : 'Simpan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endif

    {{-- Delete Modal --}}
    @if ($showDeleteModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-gray-900/40" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-xl shadow-float">
                        <div class="px-6 py-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Hapus Tahun Ajaran?</h3>
                                    <p class="text-sm text-gray-500">Tindakan ini tidak bisa dibatalkan.</p>
                                </div>
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100">
                                    Batal
                                </button>
                                <button type="button" wire:click="delete"
                                    class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium">
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