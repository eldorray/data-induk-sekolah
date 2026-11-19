{{-- Modal generate nomor induk. Dipakai halaman siswa MI dan SMP (trait GeneratesNomorInduk). --}}
<template x-teleport="#modal-portal">
    <div class="fixed inset-0 z-[9999] overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/40" wire:click="closeGenerateModal"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <form wire:submit="generateNomorInduk"
                class="relative w-full max-w-md bg-white rounded-xl shadow-float overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Generate nomor induk</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Format 2 digit tahun masuk + 4 digit nomor urut, urut abjad per kelas.
                        Nomor yang sudah ada tidak diubah.
                    </p>
                </div>

                <div class="px-6 py-6 flex flex-col gap-4">
                    <div>
                        <label for="gen-kelas" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <select id="gen-kelas" wire:model.live="genKelas"
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                            <option value="">Pilih kelas</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas }}">{{ $kelas }}</option>
                            @endforeach
                        </select>
                        @error('genKelas')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gen-tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun masuk</label>
                        <input id="gen-tahun" type="number" min="2000" max="2099" inputmode="numeric"
                            wire:model.live.debounce.300ms="genTahunMasuk"
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm font-mono tabular-nums">
                        <p class="mt-1 text-xs text-gray-500">
                            Terisi otomatis dari tingkat kelas. Ubah bila kelas ini masuk di tahun lain.
                        </p>
                        @error('genTahunMasuk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if ($pratinjau)
                        <div class="px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-700" role="status">
                            @if ($pratinjau['jumlah'] === 0)
                                Semua siswa aktif di kelas ini sudah punya nomor induk
                            @else
                                {{ $pratinjau['jumlah'] }} siswa akan diberi nomor
                                <span class="font-mono tabular-nums text-gray-900">{{ $pratinjau['mulai'] }}</span>
                                @if ($pratinjau['jumlah'] > 1)
                                    sampai <span class="font-mono tabular-nums text-gray-900">{{ $pratinjau['sampai'] }}</span>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" wire:click="closeGenerateModal"
                        class="px-3 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">
                        Batal
                    </button>
                    <button type="submit" wire:loading.attr="disabled" wire:target="generateNomorInduk"
                        @disabled(! $pratinjau || $pratinjau['jumlah'] === 0)
                        class="px-3 py-2 rounded-lg bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
