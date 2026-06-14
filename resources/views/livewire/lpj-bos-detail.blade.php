<div class="animate-fade-up space-y-6">
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
            <div>
                <a href="{{ route('lpj-bos.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-900">←
                    Kembali ke LPJ BOS</a>
                <h2 class="text-xl font-semibold text-gray-900 mt-2">{{ $lpj->nama_kegiatan }}</h2>
                <p class="text-sm text-gray-600">{{ $lpj->tanggal_kegiatan->format('d-m-Y') }} · {{ $lpj->lokasi }}</p>
                <p class="text-sm text-gray-500 mt-1">Nomor bukti: {{ $lpj->kuitansi->nomor_bukti_lengkap }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span
                    class="px-3 py-1 rounded-full text-xs font-medium {{ $lpj->is_complete ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                    {{ $lpj->completeness_label }}
                </span>
                <a href="{{ route('lpj-bos.print', $lpj->id) }}" target="_blank"
                    class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-medium">Cetak PDF</a>
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
            <div
                class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $section['title'] }}</h3>
                    <p class="text-xs text-gray-500">PDF/JPG/PNG. Gambar max 10MB, PDF max 5MB. File otomatis terunggah
                        setelah dipilih.</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="file" multiple wire:model="{{ $section['property'] }}"
                        accept=".pdf,.jpg,.jpeg,.png" wire:loading.attr="disabled"
                        wire:target="{{ $section['property'] }}"
                        class="text-sm border border-gray-200 rounded-xl px-3 py-2">
                    <span wire:loading.flex wire:target="{{ $section['property'] }}"
                        class="items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Mengunggah...
                    </span>
                </div>
            </div>
            @error($section['property'])
                <div class="px-6 pt-3 text-xs text-red-500">{{ $message }}</div>
            @enderror
            @error($section['property'] . '.*')
                <div class="px-6 pt-3 text-xs text-red-500">{{ $message }}</div>
            @enderror

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse ($section['items'] as $attachment)
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <div class="h-44 bg-gray-50 flex items-center justify-center">
                            @if ($attachment->is_image)
                                <img src="{{ $attachment->url }}" alt="{{ $attachment->original_name }}"
                                    class="max-h-44 max-w-full object-contain">
                            @else
                                <div class="text-center text-gray-500 text-sm">PDF<br>{{ $attachment->original_name }}
                                </div>
                            @endif
                        </div>
                        <div class="p-4 space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $attachment->original_name }}
                                </p>
                                <p class="text-xs text-gray-500">Urutan {{ $attachment->sort_order }}</p>
                            </div>
                            <textarea wire:model="attachmentCaptions.{{ $attachment->id }}" rows="2" placeholder="Keterangan opsional"
                                class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm"></textarea>
                            <div class="flex flex-wrap items-center gap-1.5 text-xs">
                                <button wire:click="saveCaption({{ $attachment->id }})"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-gray-900 hover:bg-gray-800 text-white font-medium shadow-sm transition-all">
                                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                    Simpan
                                </button>
                                <button wire:click="moveUp({{ $attachment->id }})"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-medium shadow-sm transition-all">
                                    <svg class="w-3 h-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                                    </svg>

                                </button>
                                <button wire:click="moveDown({{ $attachment->id }})"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-medium shadow-sm transition-all">
                                    <svg class="w-3 h-3 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                                    </svg>

                                </button>
                                <a href="{{ $attachment->url }}" target="_blank"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-blue-100 bg-blue-50/50 hover:bg-blue-50 text-blue-700 font-medium shadow-sm transition-all">
                                    <svg class="w-3 h-3 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>

                                </a>
                                <button wire:click="deleteAttachment({{ $attachment->id }})"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-red-100 bg-red-50/50 hover:bg-red-50 text-red-700 font-medium shadow-sm transition-all">
                                    <svg class="w-3 h-3 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>

                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-sm text-gray-500 py-8">Belum ada lampiran
                        {{ strtolower($section['title']) }}.</div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
