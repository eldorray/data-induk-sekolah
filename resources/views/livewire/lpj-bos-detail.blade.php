<div class="animate-fade-up space-y-6">
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
            <div>
                <a href="{{ route('lpj-bos.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">← Kembali ke LPJ BOS</a>
                <h2 class="text-xl font-semibold text-gray-900 mt-2">{{ $lpj->nama_kegiatan }}</h2>
                <p class="text-sm text-gray-600">{{ $lpj->tanggal_kegiatan->format('d-m-Y') }} · {{ $lpj->lokasi }}</p>
                <p class="text-sm text-gray-500 mt-1">Nomor bukti: {{ $lpj->kuitansi->nomor_bukti_lengkap }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $lpj->is_complete ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                    {{ $lpj->completeness_label }}
                </span>
                <a href="{{ route('lpj-bos.print', $lpj->id) }}" target="_blank" class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-medium">Cetak PDF</a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">{{ session('success') }}</div>
    @endif

    @php
        $sections = [
            'foto' => ['title' => 'Foto', 'property' => 'fotoFiles', 'items' => $fotoAttachments],
            'kwitansi' => ['title' => 'Kwitansi', 'property' => 'kwitansiFiles', 'items' => $kwitansiAttachments],
            'undangan' => ['title' => 'Surat Undangan', 'property' => 'undanganFiles', 'items' => $undanganAttachments],
        ];
    @endphp

    @foreach ($sections as $category => $section)
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $section['title'] }}</h3>
                    <p class="text-xs text-gray-500">PDF/JPG/PNG. Gambar max 10MB, PDF max 5MB. File otomatis terunggah setelah dipilih.</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="file" multiple wire:model="{{ $section['property'] }}" accept=".pdf,.jpg,.jpeg,.png"
                        wire:loading.attr="disabled" wire:target="{{ $section['property'] }}"
                        class="text-sm border border-gray-200 rounded-xl px-3 py-2">
                    <span wire:loading.flex wire:target="{{ $section['property'] }}" class="items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Mengunggah...
                    </span>
                </div>
            </div>
            @error($section['property']) <div class="px-6 pt-3 text-xs text-red-500">{{ $message }}</div> @enderror
            @error($section['property'] . '.*') <div class="px-6 pt-3 text-xs text-red-500">{{ $message }}</div> @enderror

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse ($section['items'] as $attachment)
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <div class="h-44 bg-gray-50 flex items-center justify-center">
                            @if ($attachment->is_image)
                                <img src="{{ $attachment->url }}" alt="{{ $attachment->original_name }}" class="max-h-44 max-w-full object-contain">
                            @else
                                <div class="text-center text-gray-500 text-sm">PDF<br>{{ $attachment->original_name }}</div>
                            @endif
                        </div>
                        <div class="p-4 space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $attachment->original_name }}</p>
                                <p class="text-xs text-gray-500">Urutan {{ $attachment->sort_order }}</p>
                            </div>
                            <textarea wire:model="attachmentCaptions.{{ $attachment->id }}" rows="2" placeholder="Keterangan opsional"
                                class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm"></textarea>
                            <div class="flex flex-wrap items-center gap-2 text-sm">
                                <button wire:click="saveCaption({{ $attachment->id }})" class="text-gray-900 hover:underline">Simpan</button>
                                <button wire:click="moveUp({{ $attachment->id }})" class="text-gray-600 hover:underline">Naik</button>
                                <button wire:click="moveDown({{ $attachment->id }})" class="text-gray-600 hover:underline">Turun</button>
                                <a href="{{ $attachment->url }}" target="_blank" class="text-blue-600 hover:underline">Download</a>
                                <button wire:click="deleteAttachment({{ $attachment->id }})" class="text-red-600 hover:underline">Hapus</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-sm text-gray-500 py-8">Belum ada lampiran {{ strtolower($section['title']) }}.</div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
