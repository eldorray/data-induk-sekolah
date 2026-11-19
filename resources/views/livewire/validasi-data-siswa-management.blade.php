<div class="animate-fade-up">
    @if (session('success'))
        <div class="mb-6 rounded-xl border border-madrasah-200 bg-madrasah-50 px-5 py-4 text-sm font-medium text-madrasah-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-medium text-gray-900">{{ $jumlahBelum }}</p>
                    <p class="text-xs text-gray-500">Belum dicek</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-madrasah-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-madrasah-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-medium text-gray-900">{{ $jumlahLengkap }}</p>
                    <p class="text-xs text-gray-500">Lengkap</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.48 0L3.16 16.25A2 2 0 005 19z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-medium text-gray-900">{{ $jumlahCatatan }}</p>
                    <p class="text-xs text-gray-500">Ada catatan</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">Cari kelas</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nama kelas"
                    class="w-full h-11 px-3 rounded-lg border border-gray-300 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-hidden" />
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">Sekolah</label>
                <select wire:model.live="filterSekolah"
                    class="w-full h-11 px-3 rounded-lg border border-gray-300 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-hidden">
                    <option value="">Semua</option>
                    <option value="MI">MI</option>
                    <option value="SMP">SMP</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1.5">Status</label>
                <select wire:model.live="filterStatus"
                    class="w-full h-11 px-3 rounded-lg border border-gray-300 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-hidden">
                    <option value="">Semua</option>
                    <option value="belum">Belum Dicek</option>
                    <option value="lengkap">Lengkap</option>
                    <option value="ada_catatan">Ada Catatan</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">Sekolah</th>
                        <th class="px-5 py-3 text-left font-medium">Kelas</th>
                        <th class="px-5 py-3 text-left font-medium">Status</th>
                        <th class="px-5 py-3 text-left font-medium">Wali kelas</th>
                        <th class="px-5 py-3 text-left font-medium">Catatan</th>
                        <th class="px-5 py-3 text-left font-medium">Waktu cek</th>
                        <th class="px-5 py-3 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($baris as $row)
                        <tr>
                            <td class="px-5 py-3 font-medium text-gray-700">{{ $row['sekolah'] }}</td>
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $row['tingkat_rombel'] }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $badge = match ($row['status']) {
                                        'lengkap' => 'bg-madrasah-100 text-madrasah-700',
                                        'ada_catatan' => 'bg-amber-100 text-amber-700',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-md text-xs font-medium {{ $badge }}">
                                    {{ \App\Models\ValidasiDataSiswa::STATUS_LABEL[$row['status']] }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $row['nama_pengisi'] ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-600 max-w-xs">
                                @if ($row['catatan'])
                                    <span class="whitespace-pre-line">{{ $row['catatan'] }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-500 whitespace-nowrap">
                                {{ $row['checked_at']?->format('d/m/Y H:i') ?? '-' }}
                            </td>
                            <td class="px-5 py-3 text-right">
                                @if ($row['id'] && $row['status'] === 'ada_catatan')
                                    <button type="button" wire:click="tandaiSelesai({{ $row['id'] }})"
                                        class="px-3 py-1.5 rounded-lg bg-gray-900 text-white text-xs font-medium hover:bg-gray-700 transition-colors">
                                        Tandai selesai
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-sm text-gray-500">
                                Belum ada kelas dengan siswa aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Riwayat unduhan data lengkap oleh wali kelas --}}
    <div class="mt-8 bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-medium text-gray-900 text-sm">Riwayat unduhan</h2>
            <p class="text-xs text-gray-500 mt-0.5">
                50 unduhan file Excel data siswa terakhir dari halaman publik.
            </p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">Waktu</th>
                        <th class="px-5 py-3 text-left font-medium">Sekolah</th>
                        <th class="px-5 py-3 text-left font-medium">Kelas</th>
                        <th class="px-5 py-3 text-left font-medium">Pengunduh</th>
                        <th class="px-5 py-3 text-left font-medium">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($unduhan as $log)
                        <tr>
                            <td class="px-5 py-3 text-gray-500 whitespace-nowrap">
                                {{ $log->created_at?->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-5 py-3 text-gray-700">{{ $log->sekolah }}</td>
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $log->tingkat_rombel }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $log->nama_pengisi }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $log->ip_address ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-500">
                                Belum ada unduhan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
