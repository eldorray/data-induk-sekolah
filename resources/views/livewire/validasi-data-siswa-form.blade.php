<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12 animate-fade-up">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-xs font-semibold text-blue-800 mb-3">
            Khusus Wali Kelas
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-950 tracking-tight mb-4">
            Cek Kelengkapan Data Siswa
        </h2>
        <p class="text-base text-gray-600">
            Periksa daftar siswa di kelas Anda, lalu tandai lengkap atau laporkan siswa yang belum terdaftar.
        </p>
    </div>

    @if (! $pinTersedia)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-6 py-5 text-sm text-amber-900">
            <span class="font-bold">Fitur belum diaktifkan.</span>
            Admin belum menyetel PIN akses. Silakan hubungi admin/tata usaha.
        </div>
    @elseif (! $pinOk)
        <div class="max-w-md mx-auto rounded-2xl border border-gray-200/80 bg-white p-6 sm:p-8 shadow-xs">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-gray-900">Masukkan PIN Akses</p>
                    <p class="text-xs text-gray-500">PIN diberikan oleh admin madrasah.</p>
                </div>
            </div>

            <form wire:submit="bukaAkses" class="space-y-4">
                <div>
                    <input type="password" wire:model="pinInput" inputmode="numeric" autocomplete="off"
                        placeholder="PIN akses"
                        class="w-full h-12 px-4 rounded-xl border border-gray-300 text-sm tracking-widest focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-hidden" />
                    @error('pinInput')
                        <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" @disabled($terkunci)
                    class="w-full h-12 rounded-xl bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Buka Data Siswa
                </button>
            </form>
        </div>
    @else
        <div class="space-y-6">
            @if ($suksesPesan)
                <div class="rounded-2xl border border-green-200 bg-green-50 px-6 py-4 text-sm font-semibold text-green-800">
                    {{ $suksesPesan }}
                </div>
            @endif

            <div class="rounded-2xl border border-gray-200/80 bg-white p-6 shadow-xs">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Sekolah</label>
                        <select wire:model.live="sekolah"
                            class="w-full h-11 px-3 rounded-xl border border-gray-300 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-hidden">
                            <option value="MI">MI</option>
                            <option value="SMP">SMP</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Kelas</label>
                        <select wire:model.live="tingkatRombel"
                            class="w-full h-11 px-3 rounded-xl border border-gray-300 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-hidden">
                            <option value="">-- Pilih kelas --</option>
                            @foreach ($kelasOptions as $kelas)
                                <option value="{{ $kelas['nama'] }}">
                                    {{ $kelas['nama'] }} &mdash; {{ $kelas['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('tingkatRombel')
                            <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            @if ($tingkatRombel !== '')
                <div class="rounded-2xl border border-gray-200/80 bg-white overflow-hidden shadow-xs">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                        <p class="font-bold text-gray-900 text-sm">{{ $tingkatRombel }}</p>
                        <span class="text-xs font-semibold text-gray-500">{{ $siswa->count() }} siswa aktif</span>
                    </div>

                    @if ($siswa->isEmpty())
                        <p class="px-6 py-8 text-center text-sm text-gray-500">
                            Tidak ada siswa aktif di kelas ini.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-bold w-12">No</th>
                                        <th class="px-6 py-3 text-left font-bold">Nama Lengkap</th>
                                        <th class="px-6 py-3 text-left font-bold">Tanggal Lahir</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($siswa as $index => $row)
                                        <tr>
                                            <td class="px-6 py-3 text-gray-500">{{ $index + 1 }}</td>
                                            <td class="px-6 py-3 font-semibold text-gray-900">{{ $row->nama_lengkap }}</td>
                                            <td class="px-6 py-3 text-gray-600">
                                                {{ $row->tanggal_lahir?->format('d/m/Y') ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="rounded-2xl border border-gray-200/80 bg-white p-6 shadow-xs space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Wali Kelas <span class="text-red-600">*</span></label>
                        <input type="text" wire:model="namaPengisi" placeholder="Nama lengkap Anda"
                            class="w-full h-11 px-3 rounded-xl border border-gray-300 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-hidden" />
                        @error('namaPengisi')
                            <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" wire:click="tandaiLengkap"
                            class="flex-1 h-12 rounded-xl bg-green-600 text-white font-bold text-sm hover:bg-green-700 transition-colors">
                            Data Sudah Lengkap
                        </button>
                    </div>

                    @if ($kelasLengkap)
                        <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">
                            <p class="text-sm font-bold text-blue-900 mb-1">Kelas ini sudah ditandai lengkap</p>
                            <p class="text-xs text-blue-800 mb-3">
                                Anda bisa mengunduh data siswa kelas ini dalam format Excel.
                                File berisi data pribadi siswa &mdash; simpan dengan hati-hati dan jangan disebarkan.
                            </p>
                            <button type="button" wire:click="unduhExcel" wire:loading.attr="disabled"
                                class="inline-flex items-center justify-center gap-2 h-11 px-5 rounded-xl bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 transition-colors disabled:opacity-60">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                <span>Unduh Data Lengkap (Excel)</span>
                            </button>
                        </div>
                    @endif

                    <div class="pt-5 border-t border-gray-100">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">
                            Siswa yang belum ada di sistem
                        </label>
                        <p class="text-xs text-gray-500 mb-2">Tulis satu nama per baris. Catatan ini dikirim ke admin.</p>
                        <textarea wire:model="catatan" rows="4"
                            placeholder="Contoh:&#10;Zahra Aulia&#10;Muhammad Rizky"
                            class="w-full px-3 py-2 rounded-xl border border-gray-300 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-hidden"></textarea>
                        @error('catatan')
                            <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                        <button type="button" wire:click="kirimCatatan"
                            class="mt-3 w-full h-12 rounded-xl bg-amber-500 text-white font-bold text-sm hover:bg-amber-600 transition-colors">
                            Kirim Catatan ke Admin
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
