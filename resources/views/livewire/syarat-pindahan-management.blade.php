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

    {{-- Add New --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-4">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Tambah Syarat Baru</h3>
        <form wire:submit="addSyarat" class="flex flex-col sm:flex-row gap-2">
            <input type="text" wire:model="newSyarat" placeholder="Tuliskan syarat pindahan..."
                class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
            <button type="submit"
                class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium flex items-center justify-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah
            </button>
        </form>
        @error('newSyarat')
            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
        @enderror
        <p class="mt-2 text-xs text-gray-500">
            Syarat ini akan tercetak di semua <strong>Surat Menerima Siswa Pindahan</strong> yang berstatus disetujui.
        </p>
    </div>

    {{-- List --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Daftar Syarat</h3>
                <p class="text-xs text-gray-500 mt-0.5">{{ $syarats->count() }} item &middot; urutkan dengan tombol panah</p>
            </div>
        </div>

        <ul class="divide-y divide-gray-100">
            @forelse($syarats as $i => $item)
                <li class="px-6 py-3 flex items-center gap-3 {{ $item->is_active ? '' : 'bg-gray-50 opacity-60' }}">
                    {{-- Number --}}
                    <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center text-sm font-semibold flex-shrink-0">
                        {{ $i + 1 }}
                    </div>

                    {{-- Syarat text / edit --}}
                    <div class="flex-1 min-w-0">
                        @if ($editingId === $item->id)
                            <form wire:submit="saveEdit" class="flex gap-2">
                                <input type="text" wire:model="editingValue"
                                    class="flex-1 px-3 py-1.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm"
                                    autofocus>
                                <button type="submit"
                                    class="px-3 py-1.5 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-xs font-medium">
                                    Simpan
                                </button>
                                <button type="button" wire:click="cancelEdit"
                                    class="px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-medium">
                                    Batal
                                </button>
                            </form>
                            @error('editingValue')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        @else
                            <div class="text-sm text-gray-900 {{ $item->is_active ? '' : 'line-through' }}">
                                {{ $item->syarat }}
                            </div>
                            @if (! $item->is_active)
                                <span class="text-xs text-gray-500">Nonaktif</span>
                            @endif
                        @endif
                    </div>

                    {{-- Actions --}}
                    @if ($editingId !== $item->id)
                        <div class="flex items-center gap-1 flex-shrink-0">
                            {{-- Reorder --}}
                            <button wire:click="moveUp({{ $item->id }})"
                                class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-900 transition-colors"
                                title="Naikkan urutan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                </svg>
                            </button>
                            <button wire:click="moveDown({{ $item->id }})"
                                class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-900 transition-colors"
                                title="Turunkan urutan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            {{-- Edit --}}
                            <button wire:click="startEdit({{ $item->id }})"
                                class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-900 transition-colors"
                                title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </button>

                            {{-- Toggle active --}}
                            <button wire:click="toggleActive({{ $item->id }})"
                                class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-900 transition-colors"
                                title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                @if ($item->is_active)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                @endif
                            </button>

                            {{-- Delete --}}
                            <button wire:click="deleteSyarat({{ $item->id }})"
                                wire:confirm="Yakin ingin menghapus syarat ini?"
                                class="p-1.5 rounded-lg hover:bg-red-50 text-gray-500 hover:text-red-600 transition-colors"
                                title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    @endif
                </li>
            @empty
                <li class="px-6 py-12 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <p class="text-sm">Belum ada syarat. Tambahkan syarat pertama Anda.</p>
                </li>
            @endforelse
        </ul>
    </div>
</div>
