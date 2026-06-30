<div class="animate-fade-up">
    @assets
        <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
        <style>
            .tox-tinymce-aux { z-index: 100000 !important; }
            .surat-preview {
                font-family: 'Times New Roman', Times, serif;
                font-size: 12pt; line-height: 1.4; color: #000; background: #fff; padding: 28px 32px;
            }
            .surat-preview .sp-garis { border-top: 3px solid #000; border-bottom: 1px solid #000; margin: 5px 0 12px; padding: 1px 0; }
            .surat-preview .sp-judul { text-align: center; margin-bottom: 14px; }
            .surat-preview .sp-judul h2 { font-size: 13pt; font-weight: bold; text-decoration: underline; margin: 0; text-transform: uppercase; }
            .surat-preview .sp-judul p { margin: 3px 0 0; }
            .surat-preview .sp-isi { text-align: justify; }
            .surat-preview .sp-isi ul, .surat-preview .sp-isi ol { margin: 8px 0; padding-left: 30px; }
            .surat-preview .sp-isi ul { list-style: disc; }
            .surat-preview .sp-isi ol { list-style: decimal; }
            .surat-preview .sp-isi table { border-collapse: collapse; width: 100%; margin: 8px 0; }
            .surat-preview .sp-isi table td, .surat-preview .sp-isi table th { border: 1px solid #000; padding: 4px 6px; }
            .surat-preview .sp-isi table.no-border td, .surat-preview .sp-isi table.no-border th { border: 0; }
            .surat-preview .sp-isi h1 { font-size: 14pt; margin: 10px 0; }
            .surat-preview .sp-isi blockquote { border-left: 3px solid #ccc; margin: 8px 0; padding-left: 12px; }
            .surat-preview .sp-ttd { margin-top: 24px; }
            .surat-preview .sp-ttd .sp-atas { text-align: center; margin: 0 0 2px; }
            .surat-preview .sp-ttd .sp-tempat { text-align: right; margin: 0 0 2px; }
            .surat-preview .sp-ttd .sp-sign { width: 100%; border-collapse: collapse; }
            .surat-preview .sp-ttd .sp-sign td { text-align: center; vertical-align: top; padding: 0 8px; }
            .surat-preview .sp-ttd .sp-spasi { height: 64px; }
            .surat-preview .sp-ttd .sp-nama { font-weight: bold; text-decoration: underline; }
            .surat-preview .sp-kop img { width: 100%; height: auto; }
        </style>
    @endassets

    <form wire:submit="save"
        x-data="{
            judul: $wire.entangle('judul'),
            nomor_surat: $wire.entangle('nomor_surat'),
            tempat: $wire.entangle('tempat'),
            ttd_atas: $wire.entangle('ttd_atas'),
            signers: $wire.entangle('signers'),
            tanggal_surat: $wire.entangle('tanggal_surat'),
            isi: $wire.entangle('isi'),
            get tglFormatted() {
                if (!this.tanggal_surat) return '';
                const b = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                const p = this.tanggal_surat.split('-');
                return parseInt(p[2]) + ' ' + b[parseInt(p[1]) - 1] + ' ' + p[0];
            },
            get sigRows() {
                const s = this.signers;
                const n = s.length;
                if (!n) return [];
                if (n === 3) return [[s[0], s[2]], [s[1]]]; // tengah turun ke bawah
                const rows = [];
                for (let i = 0; i < n; i += 2) rows.push(s.slice(i, i + 2));
                return rows;
            }
        }">

        {{-- Top bar --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('surat-universal.index') }}" wire:navigate
                class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke daftar surat
            </a>
            <div class="flex gap-3">
                <a href="{{ route('surat-universal.index') }}" wire:navigate
                    class="px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">Batal</a>
                <button type="submit"
                    class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium transition-colors">
                    {{ $isEditing ? 'Simpan Perubahan' : 'Simpan' }}
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            {{-- Kolom kiri: field + editor --}}
            <div class="space-y-4">
                <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Surat <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="jenis" placeholder="Contoh: Surat Tugas, Surat Izin"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                            @error('jenis') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Surat <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="judul" placeholder="Contoh: SURAT KETERANGAN"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                        @error('judul') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="nomor_surat"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                            @error('nomor_surat') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="tanggal_surat"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                            @error('tanggal_surat') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

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

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tempat</label>
                            <input type="text" wire:model="tempat" placeholder="Kota/Kabupaten"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teks di atas TTD (opsional)</label>
                            <input type="text" wire:model="ttd_atas" placeholder='Contoh: Mengetahui,'
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                        </div>
                    </div>

                    {{-- Penandatangan (repeater) --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Penandatangan</label>
                            <button type="button" wire:click="addSigner"
                                class="inline-flex items-center gap-1 text-sm text-gray-700 hover:text-gray-900 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah
                            </button>
                        </div>
                        <div class="space-y-3">
                            @foreach ($signers as $i => $signer)
                                <div wire:key="signer-{{ $i }}" class="rounded-xl border border-gray-200 p-3 bg-gray-50/50">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium text-gray-500">Penandatangan {{ $i + 1 }}</span>
                                        @if (count($signers) > 1)
                                            <button type="button" wire:click="removeSigner({{ $i }})" class="text-gray-400 hover:text-red-600" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <input type="text" wire:model="signers.{{ $i }}.jabatan" placeholder="Jabatan (mis. Kepala Madrasah)"
                                            class="px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        <input type="text" wire:model="signers.{{ $i }}.nama" placeholder="Nama"
                                            class="px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                        <input type="text" wire:model="signers.{{ $i }}.nip" placeholder="NIP (opsional)"
                                            class="px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm col-span-2">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-2 text-xs text-gray-400">1-3 ttd sebaris; 4 jadi 2&times;2; 5-6 jadi 3 per baris (otomatis).</p>
                    </div>
                </div>

                {{-- Editor --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Isi Surat <span class="text-red-500">*</span></label>
                    <div wire:ignore x-init="
                        if (tinymce.get('su-editor')) tinymce.remove('#su-editor');
                        tinymce.init({
                            selector: '#su-editor',
                            license_key: 'gpl',
                            menubar: false,
                            branding: false,
                            promotion: false,
                            statusbar: false,
                            height: 520,
                            plugins: 'lists advlist autolink link table',
                            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | table tableprops | link removeformat',
                            toolbar_mode: 'wrap',
                            table_default_styles: { 'border-collapse': 'collapse', width: '100%' },
                            table_class_list: [{ title: 'Dengan garis', value: '' }, { title: 'Tanpa garis', value: 'no-border' }],
                            content_style: &quot;body{font-family:'Times New Roman',Times,serif;font-size:12pt;line-height:1.4} p{margin:6px 0} table{border-collapse:collapse;width:100%} table td,table th{border:1px solid #000;padding:4px 6px} table.no-border td,table.no-border th{border:0}&quot;,
                            setup: (ed) => {
                                ed.on('init', () => ed.setContent($wire.isi || ''));
                                ed.on('change keyup input undo redo SetContent', () => { $wire.isi = ed.getContent(); });
                            }
                        });
                    ">
                        <textarea id="su-editor"></textarea>
                    </div>
                    @error('isi') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Kolom kanan: live preview --}}
            <div class="xl:sticky xl:top-6 self-start w-full">
                <div class="text-xs font-medium text-gray-500 mb-2">Pratinjau (tampilan print)</div>
                <div class="bg-white rounded-2xl border border-gray-200 overflow-y-auto max-h-[80vh]">
                    <div class="surat-preview">
                        <div class="sp-kop">
                            @if ($kopFile)
                                <img src="{{ $kopFile->temporaryUrl() }}" alt="">
                            @elseif ($existingKopPath)
                                <img src="{{ asset('storage/' . $existingKopPath) }}" alt="">
                            @elseif (!empty($defaultKopUrl))
                                <img src="{{ $defaultKopUrl }}" alt="">
                            @endif
                        </div>
                        <div class="sp-garis"></div>
                        <div class="sp-judul">
                            <h2 x-text="judul || 'JUDUL SURAT'"></h2>
                            <p>Nomor : <span x-text="nomor_surat"></span></p>
                        </div>
                        <div class="sp-isi" x-html="isi"></div>
                        <div class="sp-ttd" x-show="signers.length">
                            <p class="sp-atas" x-show="ttd_atas" x-text="ttd_atas"></p>
                            <p class="sp-tempat"><span x-text="tempat"></span><span x-show="tempat">, </span><span x-text="tglFormatted"></span></p>
                            <template x-for="(row, ri) in sigRows" :key="ri">
                                <table class="sp-sign"><tbody><tr>
                                    <template x-for="(s, ci) in row" :key="ci">
                                        <td>
                                            <p x-text="s.jabatan"></p>
                                            <div class="sp-spasi"></div>
                                            <p class="sp-nama" x-text="s.nama"></p>
                                            <p x-show="s.nip">NIP. <span x-text="s.nip"></span></p>
                                        </td>
                                    </template>
                                </tr></tbody></table>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
