<div class="animate-fade-up">
    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        {{-- KOP Surat Section --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">KOP Surat & Stempel</h3>
                <p class="text-sm text-gray-500 mt-1">Upload gambar KOP surat, stempel, dan tanda tangan kepala sekolah
                    untuk surat mutasi.</p>
            </div>
            <div class="px-6 py-4 space-y-4">
                {{-- KOP Surat --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">KOP Surat (Header)</label>
                    @if ($current_kop_surat)
                        <div class="mb-3 p-3 bg-gray-50 rounded-xl border">
                            <div class="flex items-center justify-between">
                                <img src="{{ asset('storage/' . $current_kop_surat) }}" alt="KOP Surat"
                                    class="max-h-24">
                                <button type="button" wire:click="deleteKop"
                                    class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                            </div>
                        </div>
                    @endif
                    <input type="file" wire:model="kop_surat" accept="image/*"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    @error('kop_surat')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maks: 2MB. Ukuran rekomendasi: 800x150 pixel
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Stempel --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stempel Sekolah</label>
                        @if ($current_stempel)
                            <div class="mb-3 p-3 bg-gray-50 rounded-xl border">
                                <div class="flex items-center justify-between">
                                    <img src="{{ asset('storage/' . $current_stempel) }}" alt="Stempel"
                                        class="max-h-20">
                                    <button type="button" wire:click="deleteStempel"
                                        class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                                </div>
                            </div>
                        @endif
                        <input type="file" wire:model="stempel" accept="image/*"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Maks: 1MB</p>
                    </div>

                    {{-- TTD Kepala --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Kepala</label>
                        @if ($current_ttd_kepala)
                            <div class="mb-3 p-3 bg-gray-50 rounded-xl border">
                                <div class="flex items-center justify-between">
                                    <img src="{{ asset('storage/' . $current_ttd_kepala) }}" alt="TTD"
                                        class="max-h-20">
                                    <button type="button" wire:click="deleteTtd"
                                        class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                                </div>
                            </div>
                        @endif
                        <input type="file" wire:model="ttd_kepala" accept="image/*"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Maks: 1MB</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Identitas Sekolah --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Identitas Sekolah</h3>
            </div>
            <div class="px-6 py-4 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sekolah <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="nama_sekolah"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                        @error('nama_sekolah')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Yayasan</label>
                        <input type="text" wire:model="nama_yayasan"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NPSN</label>
                        <input type="text" wire:model="npsn"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NSM</label>
                        <input type="text" wire:model="nsm"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Surat</label>
                        <input type="text" wire:model="kode_surat" placeholder="contoh: MIDH"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <input type="text" wire:model="alamat"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                </div>

                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan</label>
                        <input type="text" wire:model="kelurahan"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                        <input type="text" wire:model="kecamatan"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                        <input type="text" wire:model="kota"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                        <input type="text" wire:model="provinsi"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                        <input type="text" wire:model="kode_pos"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                        <input type="text" wire:model="telepon"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" wire:model="email"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                </div>
            </div>
        </div>

        {{-- Kepala Sekolah --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Kepala Sekolah</h3>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kepala Sekolah</label>
                        <input type="text" wire:model="nama_kepala"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                        <input type="text" wire:model="nip_kepala"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end">
            <button type="submit"
                class="px-6 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition-colors">
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
