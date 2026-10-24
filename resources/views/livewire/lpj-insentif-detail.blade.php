<div class="animate-fade-up">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">LPJ Insentif {{ $lpj->periode }} {{ $lpj->tahun }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $lpj->penerimas->count() }} penerima &middot; Bendahara:
                {{ $lpj->nama_bendahara }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('lpj-insentif.index') }}" wire:navigate
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium">&larr;
                Kembali</a>
            <a href="{{ route('lpj-insentif.print', $lpj->id) }}" target="_blank"
                class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium">Cetak
                PDF</a>
            <button wire:click="openCreateModal"
                class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium">+ Tambah
                Penerima</button>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Jumlah Uang</p>
            <p class="text-xl font-semibold text-gray-900 mt-1">Rp
                {{ number_format($lpj->penerimas->sum(fn ($p) => $p->jumlah_uang), 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total PPH21</p>
            <p class="text-xl font-semibold text-gray-900 mt-1">Rp
                {{ number_format($lpj->penerimas->sum('pph21'), 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Diterima (Transfer)</p>
            <p class="text-xl font-semibold text-gray-900 mt-1">Rp
                {{ number_format($lpj->penerimas->sum(fn ($p) => $p->jumlah_diterima), 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No.
                        </th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nama</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            NPWP</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            NIP/PegId/NUPTK</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Jabatan</th>
                        <th class="px-4 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Tarif &times; Vol</th>
                        <th class="px-4 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Jumlah</th>
                        <th class="px-4 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Diterima</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            No. Rekening</th>
                        <th class="px-4 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($lpj->penerimas as $p)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $p->nama }}</td>
                            <td class="px-4 py-3 text-xs font-mono text-gray-600">{{ $p->npwp ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs font-mono text-gray-600">{{ $p->nip_pegid_nuptk ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span
                                    class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $p->status_pegawai === 'PNS' ? 'bg-blue-50 text-blue-700' : ($p->status_pegawai === 'PPPK' ? 'bg-purple-50 text-purple-700' : 'bg-gray-100 text-gray-700') }}">{{ $p->status_pegawai }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $p->jabatan }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                                {{ number_format($p->tarif, 0, ',', '.') }} &times; {{ $p->volume }} {{ $p->satuan }}</td>
                            <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">
                                {{ number_format($p->jumlah_uang, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">
                                {{ number_format($p->jumlah_diterima, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-xs font-mono text-gray-600">{{ $p->no_rekening ?? '-' }}</td>
                            <td class="px-4 py-3 text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openEditModal({{ $p->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-medium transition-all shadow-sm">
                                        <svg class="w-3.5 h-3.5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.83 20.013a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>
                                    <button wire:click="openDeleteModal({{ $p->id }})"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-red-100 bg-red-50/50 hover:bg-red-50 text-red-700 text-xs font-medium transition-all shadow-sm">
                                        <svg class="w-3.5 h-3.5 text-red-500" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-12 text-center text-gray-500">Belum ada penerima. Klik
                                "Tambah Penerima" untuk mulai mengisi daftar.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($lpj->penerimas->isNotEmpty())
                    <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                        <tr class="font-semibold text-gray-900">
                            <td colspan="7" class="px-4 py-3 text-sm text-right uppercase">Jumlah</td>
                            <td class="px-4 py-3 text-sm text-right">{{ number_format($lpj->penerimas->sum(fn ($p) => $p->jumlah_uang), 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-right">{{ number_format($lpj->penerimas->sum(fn ($p) => $p->jumlah_diterima), 0, ',', '.') }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    @if ($showModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
                        <form wire:submit="save">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $isEditing ? 'Edit Penerima' : 'Tambah Penerima' }}</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                @if (! $isEditing)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Ambil dari Data
                                            Guru (opsional)</label>
                                        <select wire:model.live="selectedGuru"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                            <option value="">-- Pilih guru untuk isi otomatis --</option>
                                            @if ($guruMi->isNotEmpty())
                                                <optgroup label="Guru MI">
                                                    @foreach ($guruMi as $g)
                                                        <option value="mi-{{ $g->id }}">
                                                            {{ $g->full_name_with_title }}@if ($g->nip) (NIP: {{ $g->nip }})@endif
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                            @if ($guruSmp->isNotEmpty())
                                                <optgroup label="Guru SMP">
                                                    @foreach ($guruSmp as $g)
                                                        <option value="smp-{{ $g->id }}">
                                                            {{ $g->full_name_with_title }}@if ($g->nip) (NIP: {{ $g->nip }})@endif
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        </select>
                                        <p class="text-xs text-gray-400 mt-1">Nama, NIP/NUPTK, dan status pegawai
                                            terisi otomatis; field lain tetap bisa dilengkapi manual.</p>
                                    </div>
                                @endif
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="sm:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                        <input type="text" wire:model="nama"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                        @error('nama')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">NPWP</label>
                                        <input type="text" wire:model="npwp" maxlength="30"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                        @error('npwp')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP/PegId/NUPTK</label>
                                        <input type="text" wire:model="nip_pegid_nuptk" maxlength="30"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                        @error('nip_pegid_nuptk')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Pangkat</label>
                                        <input type="text" wire:model="pangkat"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                        @error('pangkat')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Golongan</label>
                                        <input type="text" wire:model="golongan" maxlength="10"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                        @error('golongan')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Pegawai</label>
                                        <select wire:model="status_pegawai"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                            <option value="PNS">PNS</option>
                                            <option value="PPPK">PPPK</option>
                                            <option value="NON PNS">NON PNS</option>
                                        </select>
                                        @error('status_pegawai')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                                        <input type="text" wire:model="jabatan"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                        @error('jabatan')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tarif</label>
                                        <input type="number" wire:model="tarif" min="0" step="50000"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                        @error('tarif')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Volume</label>
                                        <input type="number" wire:model="volume" min="0"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                        @error('volume')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                                        <input type="text" wire:model="satuan" maxlength="10"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                        @error('satuan')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">PPH21</label>
                                        <input type="number" wire:model="pph21" min="0" step="500"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                        @error('pph21')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Rekening</label>
                                        <input type="text" wire:model="no_rekening" maxlength="30"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm">
                                        @error('no_rekening')
                                            <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="flex items-end">
                                        <p class="text-sm text-gray-500">Jumlah Uang: <strong>Rp
                                                {{ number_format((int) $tarif * (int) $volume, 0, ',', '.') }}</strong>
                                            &middot; Diterima: <strong>Rp
                                                {{ number_format(max(0, (int) $tarif * (int) $volume - (int) $pph21), 0, ',', '.') }}</strong></p>
                                    </div>
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus Penerima</h3>
                        <p class="text-sm text-gray-600 mb-4">Data penerima ini akan dihapus dari LPJ.</p>
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
