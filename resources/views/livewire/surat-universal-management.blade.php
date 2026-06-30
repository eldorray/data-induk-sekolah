<div class="animate-fade-up">
    @assets
        <link rel="stylesheet" href="https://unpkg.com/trix@2.1.15/dist/trix.css">
        <script src="https://unpkg.com/trix@2.1.15/dist/trix.umd.min.js"></script>
        <style>
            trix-editor { min-height: 240px; border-radius: 0.75rem; border-color: #e5e7eb; }
            trix-editor:empty:not(:focus)::before { color: #9ca3af; }
        </style>
    @endassets

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
            <select wire:model.live="filterJenjang"
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                <option value="">Semua Jenjang</option>
                @foreach ($jenjangOptions as $opt)
                    <option value="{{ $opt }}">{{ $opt }}</option>
                @endforeach
            </select>
        </div>
        <button wire:click="openCreateModal"
            class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Buat Surat
        </button>
    </div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">{{ session('success') }}</div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nomor Surat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenjang</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($surats as $index => $surat)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $surats->firstItem() + $index }}</td>
                            <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $surat->nomor_surat }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $surat->jenis }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $surat->judul }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $surat->jenjang ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('surat-universal.print', $surat->id) }}" target="_blank"
                                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors" title="Print PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                    </a>
                                    <button wire:click="openEditModal({{ $surat->id }})"
                                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="openDeleteModal({{ $surat->id }})"
                                        class="p-2 rounded-lg hover:bg-red-50 text-gray-600 hover:text-red-600 transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <p class="text-sm">Belum ada data surat</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($surats->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">{{ $surats->links() }}</div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
                        <form wire:submit="save">
                            <div class="border-b border-gray-200 px-6 py-4 sticky top-0 bg-white z-10">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $isEditing ? 'Edit Surat' : 'Buat Surat' }}</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    {{-- Jenis --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Surat <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model="jenis" placeholder="Contoh: Surat Tugas, Surat Izin"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        @error('jenis') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                    </div>
                                    {{-- Jenjang --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenjang <span class="text-red-500">*</span></label>
                                        <select wire:model="jenjang"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                            @foreach ($jenjangOptions as $opt)
                                                <option value="{{ $opt }}">{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                        @error('jenjang') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                {{-- Judul --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Surat <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="judul" placeholder="Contoh: SURAT KETERANGAN"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    @error('judul') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    {{-- Nomor --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model="nomor_surat"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        @error('nomor_surat') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                    </div>
                                    {{-- Tanggal --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat <span class="text-red-500">*</span></label>
                                        <input type="date" wire:model="tanggal_surat"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        @error('tanggal_surat') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                {{-- Kop Surat --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kop Surat (gambar)</label>
                                    <input type="file" wire:model="kopFile" accept="image/*"
                                        class="block w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gray-900 file:text-white file:text-sm hover:file:bg-gray-800">
                                    <div wire:loading wire:target="kopFile" class="mt-1 text-xs text-gray-500">Mengunggah...</div>
                                    @error('kopFile') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                    @if ($kopFile)
                                        <img src="{{ $kopFile->temporaryUrl() }}" class="mt-2 max-h-24 rounded-lg border border-gray-200">
                                    @elseif ($existingKopPath)
                                        <img src="{{ asset('storage/' . $existingKopPath) }}" class="mt-2 max-h-24 rounded-lg border border-gray-200">
                                    @endif
                                    <p class="mt-1 text-xs text-gray-400">Kosongkan untuk pakai kop sekolah default.</p>
                                </div>

                                {{-- Isi (Trix) --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Isi Surat <span class="text-red-500">*</span></label>
                                    <div wire:ignore x-data x-init="
                                        const el = $refs.trix;
                                        const load = () => { if (el.editor) el.editor.loadHTML($wire.isi || ''); };
                                        el.addEventListener('trix-initialize', load);
                                        el.addEventListener('trix-change', () => $wire.isi = el.value);
                                        el.addEventListener('trix-file-accept', (e) => e.preventDefault());
                                        if (el.editor) load();
                                    ">
                                        <input id="trix-isi-input" type="hidden">
                                        <trix-editor x-ref="trix" input="trix-isi-input"></trix-editor>
                                    </div>
                                    @error('isi') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                                </div>

                                {{-- Tempat + ttd --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat</label>
                                        <input type="text" wire:model="tempat" placeholder="Kota/Kabupaten"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan Penandatangan</label>
                                        <input type="text" wire:model="ttd_jabatan" placeholder="Kepala Madrasah"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penandatangan</label>
                                        <input type="text" wire:model="ttd_nama"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP (opsional)</label>
                                        <input type="text" wire:model="ttd_nip"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 px-6 py-4 flex justify-end gap-3 sticky bottom-0 bg-white">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition-colors">{{ $isEditing ? 'Simpan Perubahan' : 'Simpan' }}</button>
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
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl">
                        <div class="p-6 text-center">
                            <svg class="w-16 h-16 mx-auto text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus Surat?</h3>
                            <p class="text-gray-500 mb-6">Tindakan ini tidak dapat dibatalkan.</p>
                            <div class="flex justify-center gap-3">
                                <button wire:click="closeModal"
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">Batal</button>
                                <button wire:click="delete"
                                    class="px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-colors">Ya, Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
