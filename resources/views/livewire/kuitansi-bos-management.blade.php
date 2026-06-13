<div class="animate-fade-up">
    {{-- Header Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kuitansi..."
                    class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent w-64 text-sm">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="openSettingsModal"
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Pengaturan
            </button>
            <button wire:click="openCreateModal"
                class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Kuitansi
            </button>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Bulk action bar --}}
    @if (count($selected) > 0)
        <div class="mb-4 p-3 rounded-xl bg-gray-900 text-white flex items-center justify-between">
            <span class="text-sm">{{ count($selected) }} kuitansi dipilih</span>
            <div class="flex items-center gap-2">
                <a href="{{ route('kuitansi-bos.print-selected', ['ids' => implode(',', $selected)]) }}"
                    target="_blank"
                    class="px-3 py-1.5 rounded-lg bg-white text-gray-900 text-sm font-medium hover:bg-gray-100 transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Cetak Terpilih
                </a>
                <button wire:click="$set('selected', [])"
                    class="px-3 py-1.5 rounded-lg text-white/80 hover:text-white text-sm transition-colors">
                    Batal
                </button>
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left w-10">
                            <span class="sr-only">Pilih</span>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nomor Bukti</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Penerima</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Uraian</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Jumlah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Tgl Lunas</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kuitansis as $kuitansi)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <input type="checkbox" wire:model.live="selected" value="{{ $kuitansi->id }}"
                                    class="w-4 h-4 rounded text-gray-900 border-gray-300 focus:ring-gray-900">
                            </td>
                            <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $kuitansi->nomor_bukti_lengkap }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $kuitansi->penerima }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate"
                                title="{{ $kuitansi->uraian_pembayaran }}">{{ $kuitansi->uraian_pembayaran }}</td>
                            <td class="px-6 py-4 text-sm text-right font-medium text-gray-900">
                                {{ $kuitansi->jumlah_format }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $kuitansi->tanggal_lunas->translatedFormat('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('kuitansi-bos.print', $kuitansi->id) }}" target="_blank"
                                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors"
                                        title="Cetak PDF">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                            </path>
                                        </svg>
                                    </a>
                                    <button wire:click="openEditModal({{ $kuitansi->id }})"
                                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="openDeleteModal({{ $kuitansi->id }})"
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
                                        d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z">
                                    </path>
                                </svg>
                                <p class="text-sm">Belum ada kuitansi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($kuitansis->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $kuitansis->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
                        <form wire:submit="save">
                            <div class="border-b border-gray-200 px-6 py-4 sticky top-0 bg-white z-10">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $isEditing ? 'Edit Kuitansi' : 'Buat Kuitansi' }}
                                </h3>
                            </div>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-6 py-4">
                            <div class="space-y-4">
                                {{-- Nomor Bukti --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Urut Bukti
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model.live="nomor_bukti" placeholder="mis. 001"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    <p class="mt-1 text-xs text-gray-500">Nomor lengkap:
                                        <span class="font-mono text-gray-700">{{ $this->nomorBuktiPreview }}</span>
                                    </p>
                                    @error('nomor_bukti')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Penerima --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Penerima
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model.live.debounce.400ms="penerima"
                                        placeholder="mis. Abdul Hamid, S.Pd / Biznet"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    @error('penerima')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Jumlah Uang (input terformat Rp via Alpine) --}}
                                <div x-data="{ raw: $wire.entangle('jumlah_uang') }">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Uang
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" inputmode="numeric"
                                        :value="raw ? 'Rp ' + Number(raw).toLocaleString('id-ID') : ''"
                                        @input="
                                            let digits = $event.target.value.replace(/[^0-9]/g, '');
                                            raw = digits ? parseInt(digits) : null;
                                        "
                                        placeholder="Rp 0"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    @error('jumlah_uang')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Terbilang (read-only, live preview) --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Terbilang
                                        <span class="text-xs font-normal text-gray-400">(otomatis)</span></label>
                                    <div
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-700 italic">
                                        {{ $this->terbilangPreview }}
                                    </div>
                                </div>

                                {{-- Uraian Pembayaran --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Uraian Pembayaran
                                        <span class="text-red-500">*</span></label>
                                    <textarea wire:model.live.debounce.400ms="uraian_pembayaran" rows="2"
                                        placeholder="mis. Honor GBPNS April 2026 a/n Abdul Hamid dkk"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm"></textarea>
                                    @error('uraian_pembayaran')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Tanggal Lunas --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lunas
                                        <span class="text-red-500">*</span></label>
                                    <input type="date" wire:model.live="tanggal_lunas"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    @error('tanggal_lunas')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                    @if ($this->tahunWarning)
                                        <p class="mt-1 text-xs text-amber-600 flex items-start gap-1">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                </path>
                                            </svg>
                                            {{ $this->tahunWarning }}
                                        </p>
                                    @endif
                                </div>
                            </div>{{-- /kolom form --}}

                            {{-- Live Preview (F4) --}}
                            <div class="lg:border-l lg:border-gray-100 lg:pl-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Preview Kuitansi
                                    <span class="text-xs font-normal text-gray-400">(F4 — berubah otomatis)</span>
                                </label>
                                <div class="rounded-xl border border-gray-200 bg-gray-100 p-3 lg:sticky lg:top-20">
                                    <div class="mx-auto bg-white text-black font-serif overflow-hidden border border-black"
                                        style="width:100%; aspect-ratio:215.9/330.2; padding:14px 18px; font-size:10px; line-height:1.45;">
                                        <div class="text-center font-bold mb-3" style="font-size:12px;">
                                            KUITANSI/BUKTI PEMBAYARAN</div>

                                        {{-- Tahun & Nomor (kanan) --}}
                                        <div class="flex mb-3">
                                            <div class="w-1/2"></div>
                                            <div class="w-1/2">
                                                <div class="flex"><span class="shrink-0"
                                                        style="width:84px;">Tahun Anggaran</span><span>:
                                                        {{ $settings['kuitansi_tahun_anggaran'] ?? '2026' }}</span>
                                                </div>
                                                <div class="flex"><span class="shrink-0"
                                                        style="width:84px;">Nomor Bukti</span><span>:
                                                        {{ $this->nomorBuktiPreview }}</span></div>
                                            </div>
                                        </div>

                                        {{-- Data utama --}}
                                        <div class="space-y-px">
                                            @php
                                                $rows = [
                                                    ['Sudah terima dari', $settings['kuitansi_sudah_terima_dari'] ?? ''],
                                                    ['Madrasah', $settings['kuitansi_nama_madrasah'] ?? ''],
                                                    ['Desa/Kecamatan', $settings['kuitansi_desa_kecamatan'] ?? ''],
                                                    ['Kabupaten', $settings['kuitansi_kabupaten'] ?? ''],
                                                    ['Provinsi', $settings['kuitansi_provinsi'] ?? ''],
                                                    ['Jumlah Uang', $jumlah_uang ? 'Rp ' . number_format((int) $jumlah_uang, 0, ',', '.') : 'Rp 0'],
                                                    ['Terbilang', $this->terbilangPreview],
                                                    ['Untuk Pembayaran', $uraian_pembayaran ?: '...'],
                                                    ['Sumber Dana', $settings['kuitansi_sumber_dana'] ?? ''],
                                                ];
                                            @endphp
                                            @foreach ($rows as [$label, $value])
                                                <div class="flex">
                                                    <span class="shrink-0" style="width:110px;">{{ $label }}</span>
                                                    <span class="flex-1">: {{ $value }}</span>
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- Penerima (kanan) --}}
                                        <div class="flex" style="margin-top:26px;">
                                            <div class="w-1/2"></div>
                                            <div class="w-1/2">
                                                <div>Penerima Uang</div>
                                                <div>Tanda Tangan</div>
                                                <div style="height:38px;"></div>
                                                <div class="underline">({{ $penerima ?: '...' }})</div>
                                            </div>
                                        </div>

                                        {{-- Tanggal lunas (kanan) --}}
                                        <div class="flex" style="margin-top:10px;">
                                            <div class="w-1/2"></div>
                                            <div class="w-1/2">Lunas dibayar tanggal
                                                {{ $tanggal_lunas ? \Illuminate\Support\Carbon::parse($tanggal_lunas)->format('d-m-Y') : '-' }}
                                            </div>
                                        </div>

                                        {{-- Kepala (kiri) & Bendahara (kanan) --}}
                                        <div class="flex" style="margin-top:2px;">
                                            <div class="w-1/2">
                                                <div>Kepala Madrasah</div>
                                                <div>Tanda Tangan</div>
                                                <div style="height:38px;"></div>
                                                <div class="underline">
                                                    ({{ $settings['kuitansi_kepala_madrasah'] ?? '' }})</div>
                                            </div>
                                            <div class="w-1/2">
                                                <div>Bendahara Madrasah</div>
                                                <div>Tanda Tangan</div>
                                                <div style="height:38px;"></div>
                                                <div class="underline">
                                                    ({{ $settings['kuitansi_bendahara_madrasah'] ?? '' }})</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>{{-- /preview --}}
                            </div>{{-- /grid --}}
                            <div
                                class="border-t border-gray-200 px-6 py-4 flex justify-end gap-3 sticky bottom-0 bg-white">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-medium transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition-colors">
                                    {{ $isEditing ? 'Simpan Perubahan' : 'Simpan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endif

    {{-- Settings Modal --}}
    @if ($showSettingsModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeSettingsModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
                        <form wire:submit="saveSettings">
                            <div class="border-b border-gray-200 px-6 py-4 sticky top-0 bg-white z-10">
                                <h3 class="text-lg font-semibold text-gray-900">Pengaturan Kuitansi</h3>
                                <p class="text-xs text-gray-500 mt-1">Data tetap lembaga — diisi sekali, dipakai di
                                    semua kuitansi.</p>
                            </div>
                            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Anggaran</label>
                                    <input type="text" wire:model="set_tahun_anggaran"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    @error('set_tahun_anggaran')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Format Nomor
                                        Bukti</label>
                                    <input type="text" wire:model="set_format_nomor"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm font-mono">
                                    <p class="mt-1 text-xs text-gray-500">Gunakan <code>...</code> sebagai posisi nomor
                                        urut.</p>
                                    @error('set_format_nomor')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Madrasah</label>
                                    <input type="text" wire:model="set_nama_madrasah"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    @error('set_nama_madrasah')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Desa /
                                        Kecamatan</label>
                                    <input type="text" wire:model="set_desa_kecamatan"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten</label>
                                    <input type="text" wire:model="set_kabupaten"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                                    <input type="text" wire:model="set_provinsi"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Sudah Terima
                                        Dari</label>
                                    <input type="text" wire:model="set_sudah_terima_dari"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Sumber Dana</label>
                                    <input type="text" wire:model="set_sumber_dana"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kepala Madrasah</label>
                                    <input type="text" wire:model="set_kepala_madrasah"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    @error('set_kepala_madrasah')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Bendahara
                                        Madrasah</label>
                                    <input type="text" wire:model="set_bendahara_madrasah"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    @error('set_bendahara_madrasah')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div
                                class="border-t border-gray-200 px-6 py-4 flex justify-end gap-3 sticky bottom-0 bg-white">
                                <button type="button" wire:click="closeSettingsModal"
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-medium transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition-colors">
                                    Simpan Pengaturan
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
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="$set('showDeleteModal', false)">
                </div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl">
                        <div class="px-6 py-5">
                            <div class="flex items-center gap-3 mb-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Hapus Kuitansi</h3>
                            </div>
                            <p class="text-sm text-gray-600">Apakah Anda yakin ingin menghapus kuitansi ini? Tindakan
                                ini tidak dapat dibatalkan.</p>
                        </div>
                        <div class="border-t border-gray-200 px-6 py-4 flex justify-end gap-3">
                            <button type="button" wire:click="$set('showDeleteModal', false)"
                                class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-medium transition-colors">
                                Batal
                            </button>
                            <button type="button" wire:click="delete"
                                class="px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-colors">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
