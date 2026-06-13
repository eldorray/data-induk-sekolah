<div class="animate-fade-up">
    <div class="flex flex-col gap-4 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kegiatan/kuitansi/penerima..."
                    class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm w-72">

                <select wire:model.live="filterTahun" class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm">
                    <option value="">Semua Tahun</option>
                    @foreach ($tahunOptions as $tahun)
                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                    @endforeach
                </select>

                <input type="date" wire:model.live="tanggalAwal" class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm">
                <input type="date" wire:model.live="tanggalAkhir" class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm">

                <select wire:model.live="filterKelengkapan" class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm">
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
            ]) }}" target="_blank"
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nomor Bukti</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kegiatan/Uraian</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Penerima</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Nominal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lampiran</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
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
                            <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $kuitansi->nomor_bukti_lengkap }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="font-medium">{{ $lpj?->nama_kegiatan ?? $kuitansi->uraian_pembayaran }}</div>
                                <div class="text-xs text-gray-500">{{ $lpj?->tanggal_kegiatan?->format('d-m-Y') ?? 'Belum ada tanggal kegiatan' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $kuitansi->penerima }}</td>
                            <td class="px-6 py-4 text-sm text-right font-medium text-gray-900">{{ $kuitansi->jumlah_format }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if ($lpj)
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">Ada LPJ</span>
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $lpj->is_complete ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                                        {{ $lpj->completeness_label }}
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">Belum Ada LPJ</span>
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
                                        <button wire:click="openEditModal({{ $lpj->id }})" class="text-gray-700 hover:text-gray-900">Edit</button>
                                        <a href="{{ route('lpj-bos.show', $lpj->id) }}" wire:navigate class="text-blue-600 hover:text-blue-800">Lampiran</a>
                                        <a href="{{ route('lpj-bos.print', $lpj->id) }}" target="_blank" class="text-green-600 hover:text-green-800">Cetak</a>
                                        <button wire:click="openDeleteModal({{ $lpj->id }})" class="text-red-600 hover:text-red-800">Hapus</button>
                                    @else
                                        <button wire:click="openCreateModal({{ $kuitansi->id }})" class="text-gray-900 font-medium hover:underline">Buat LPJ</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">Belum ada kuitansi BOS.</td>
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
                                <h3 class="text-lg font-semibold text-gray-900">{{ $isEditing ? 'Edit LPJ BOS' : 'Buat LPJ BOS' }}</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan</label>
                                    <input type="text" wire:model="nama_kegiatan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                    @error('nama_kegiatan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kegiatan</label>
                                    <input type="date" wire:model="tanggal_kegiatan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                    @error('tanggal_kegiatan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                                    <input type="text" wire:model="lokasi" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                    @error('lokasi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                    <textarea wire:model="catatan" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm"></textarea>
                                    @error('catatan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="border-t border-gray-200 px-6 py-4 flex justify-end gap-2">
                                <button type="button" wire:click="closeModal" class="px-4 py-2 rounded-xl border border-gray-200 text-sm">Batal</button>
                                <button type="submit" class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm">Simpan</button>
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
                            <button type="button" wire:click="closeModal" class="px-4 py-2 rounded-xl border border-gray-200 text-sm">Batal</button>
                            <button type="button" wire:click="delete" class="px-4 py-2 rounded-xl bg-red-600 text-white text-sm">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
