<div class="animate-fade-up">
    <div class="flex flex-col gap-4 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Cari kegiatan/kuitansi/penerima..."
                    class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm w-72">

                <select wire:model.live="filterTahun"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm">
                    <option value="">Semua Tahun</option>
                    @foreach ($tahunOptions as $tahun)
                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                    @endforeach
                </select>

                <input type="date" wire:model.live="tanggalAwal"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm">
                <input type="date" wire:model.live="tanggalAkhir"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm">

                <select wire:model.live="filterKelengkapan"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm">
                    <option value="">Semua Status</option>
                    <option value="lengkap">Lengkap</option>
                    <option value="belum_lengkap">Belum lengkap</option>
                </select>
            </div>

            <a href="{{ route('lpj-bos.print-rekap', [
                'search' => $search,
                'tahun' => $filterTahun,
                'tanggal_awal' => $tanggalAwal,
                'tanggal_akhir' => $tanggalAkhir,
                'kelengkapan' => $filterKelengkapan,
            ]) }}"
                target="_blank"
                class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium">
                Cetak Rekap
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nomor Bukti</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Kegiatan/Uraian</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Penerima</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nominal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Lampiran</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kuitansis as $kuitansi)
                        @php
                            $lpj = $kuitansi->lpjBos;
                            $fotoCount = $lpj?->attachmentCount('foto') ?? 0;
                            $kwitansiCount = $lpj?->attachmentCount('kwitansi') ?? 0;
                            $undanganCount = $lpj?->attachmentCount('undangan') ?? 0;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $kuitansi->nomor_bukti_lengkap }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="font-medium">{{ $lpj?->nama_kegiatan ?? $kuitansi->uraian_pembayaran }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $lpj?->tanggal_kegiatan?->format('d-m-Y') ?? 'Belum ada tanggal kegiatan' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $kuitansi->penerima }}</td>
                            <td class="px-6 py-4 text-sm text-right font-medium text-gray-900">
                                {{ $kuitansi->jumlah_format }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if ($lpj)
                                    <span
                                        class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">Ada
                                        LPJ</span>
                                    <span
                                        class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $lpj->is_complete ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                                        {{ $lpj->completeness_label }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">Belum
                                        Ada LPJ</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600">
                                Foto: {{ $fotoCount }}<br>
                                Kwitansi: {{ $kwitansiCount }}<br>
                                Undangan: {{ $undanganCount }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($lpj)
                                        <button wire:click="openEditModal({{ $lpj->id }})"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 hover:text-gray-900 hover:border-gray-300 text-xs font-medium transition-all shadow-sm">
                                            <svg class="w-3.5 h-3.5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.83 20.013a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                            </svg>

                                        </button>
                                        <a href="{{ route('lpj-bos.show', $lpj->id) }}" wire:navigate
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-blue-100 bg-blue-50/50 hover:bg-blue-50 text-blue-700 hover:text-blue-800 text-xs font-medium transition-all shadow-sm">
                                            <svg class="w-3.5 h-3.5 text-blue-500" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32a1.5 1.5 0 0 1-2.12-2.12l10.94-10.94" />
                                            </svg>

                                        </a>
                                        <a href="{{ route('lpj-bos.print', $lpj->id) }}" target="_blank"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-emerald-100 bg-emerald-50/50 hover:bg-emerald-50 text-emerald-700 hover:text-emerald-800 text-xs font-medium transition-all shadow-sm">
                                            <svg class="w-3.5 h-3.5 text-emerald-500" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.615 0-1.11-.474-1.12-1.088L6 18m12 0H6m12 0a1.8 1.8 0 0 0 1.8-1.8v-3.6a1.8 1.8 0 0 0-1.8-1.8H6a1.8 1.8 0 0 0-1.8 1.8v3.6a1.8 1.8 0 0 0 1.8 1.8m12 0a1.8 1.8 0 0 1-1.8-1.8v-3.6A1.8 1.8 0 0 1 12 10.8h-1.2m0 0a1.8 1.8 0 0 1-1.8-1.8V5.4a1.8 1.8 0 0 1 1.8-1.8h2.4a1.8 1.8 0 0 1 1.8 1.8v3.6a1.8 1.8 0 0 1-1.8 1.8H12" />
                                            </svg>

                                        </a>
                                        <button wire:click="openDeleteModal({{ $lpj->id }})"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-red-100 bg-red-50/50 hover:bg-red-50 text-red-700 hover:text-red-800 text-xs font-medium transition-all shadow-sm">
                                            <svg class="w-3.5 h-3.5 text-red-500" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>

                                        </button>
                                    @else
                                        <button wire:click="openCreateModal({{ $kuitansi->id }})"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-xs font-medium transition-all shadow-sm">
                                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>
                                            Buat LPJ
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">Belum ada kuitansi BOS.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($kuitansis->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">{{ $kuitansis->links() }}</div>
        @endif
    </div>

    @if ($showModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl">
                        <form wire:submit="save">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $isEditing ? 'Edit LPJ BOS' : 'Buat LPJ BOS' }}</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan</label>
                                    <input type="text" wire:model="nama_kegiatan"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                    @error('nama_kegiatan')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                        Kegiatan</label>
                                    <input type="date" wire:model="tanggal_kegiatan"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                    @error('tanggal_kegiatan')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                                    <input type="text" wire:model="lokasi"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                    @error('lokasi')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                    <textarea wire:model="catatan" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm"></textarea>
                                    @error('catatan')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="border-t border-gray-200 px-6 py-4 flex justify-end gap-2">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2 rounded-xl border border-gray-200 text-sm">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm">Simpan</button>
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus LPJ BOS</h3>
                        <p class="text-sm text-gray-600 mb-4">Semua lampiran LPJ juga akan dihapus.</p>
                        <div class="flex justify-end gap-2">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 rounded-xl border border-gray-200 text-sm">Batal</button>
                            <button type="button" wire:click="delete"
                                class="px-4 py-2 rounded-xl bg-red-600 text-white text-sm">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
