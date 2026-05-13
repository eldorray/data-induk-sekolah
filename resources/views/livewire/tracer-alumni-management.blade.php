<div class="animate-fade-up">
    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $total }}</p>
                    <p class="text-xs text-gray-500">Total Responden</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalMi }}</p>
                    <p class="text-xs text-gray-500">Alumni MI</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 14l9-5-9-5-9 5 9 5z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalSmp }}</p>
                    <p class="text-xs text-gray-500">Alumni SMP</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-yellow-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $avgKepuasan ? number_format($avgKepuasan, 1) : '-' }}
                    </p>
                    <p class="text-xs text-gray-500">Rata-rata Kepuasan</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Distribution --}}
    @if (count($statusDistribution) > 0)
        <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Distribusi Status Alumni</h3>
            <div class="flex flex-wrap gap-3">
                @foreach ($statusDistribution as $status => $count)
                    @php
                        $colors = [
                            'Kuliah' => 'bg-blue-100 text-blue-700',
                            'Bekerja' => 'bg-green-100 text-green-700',
                            'Wirausaha' => 'bg-purple-100 text-purple-700',
                            'Belum Bekerja' => 'bg-yellow-100 text-yellow-700',
                            'Lainnya' => 'bg-gray-100 text-gray-700',
                        ];
                        $color = $colors[$status] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium {{ $color }}">
                        {{ $status }}
                        <span class="font-bold">{{ $count }}</span>
                        @if ($total > 0)
                            <span class="text-xs opacity-70">({{ number_format(($count / $total) * 100, 0) }}%)</span>
                        @endif
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Header Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari alumni..."
                    class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent w-64 text-sm">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <select wire:model.live="filterJenjang"
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                <option value="">Semua Jenjang</option>
                <option value="MI">MI</option>
                <option value="SMP">SMP</option>
            </select>
            <select wire:model.live="filterStatus"
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                <option value="">Semua Status</option>
                <option value="Kuliah">Kuliah</option>
                <option value="Bekerja">Bekerja</option>
                <option value="Wirausaha">Wirausaha</option>
                <option value="Belum Bekerja">Belum Bekerja</option>
                <option value="Lainnya">Lainnya</option>
            </select>
            @if (count($tahunLulusOptions) > 0)
                <select wire:model.live="filterTahunLulus"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                    <option value="">Semua Tahun</option>
                    @foreach ($tahunLulusOptions as $tahun)
                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                    @endforeach
                </select>
            @endif
            <select wire:model.live="perPage"
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="exportCsv"
                class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Export CSV
            </button>
            <a href="{{ route('tracer-alumni.form') }}" target="_blank"
                class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                Lihat Form
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            No</th>
                        <th wire:click="sortBy('nama_lengkap')"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-gray-900">
                            <div class="flex items-center gap-1">
                                Nama
                                @if ($sortField === 'nama_lengkap')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 15l7-7 7 7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Jenjang</th>
                        <th wire:click="sortBy('tahun_lulus')"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-gray-900">
                            <div class="flex items-center gap-1">
                                Tahun Lulus
                                @if ($sortField === 'tahun_lulus')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 15l7-7 7 7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Institusi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Kontak</th>
                        <th wire:click="sortBy('created_at')"
                            class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:text-gray-900">
                            <div class="flex items-center gap-1">
                                Tanggal Isi
                                @if ($sortField === 'created_at')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'desc' ? 'rotate-180' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 15l7-7 7 7" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($data as $index => $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $data->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $item->nama_lengkap }}</div>
                                @if ($item->nisn)
                                    <div class="text-xs text-gray-500">NISN: {{ $item->nisn }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex px-2.5 py-1 rounded-lg text-xs font-medium {{ $item->jenjang === 'MI' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ $item->jenjang }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->tahun_lulus }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'Kuliah' => 'bg-blue-100 text-blue-700',
                                        'Bekerja' => 'bg-green-100 text-green-700',
                                        'Wirausaha' => 'bg-purple-100 text-purple-700',
                                        'Belum Bekerja' => 'bg-yellow-100 text-yellow-700',
                                        'Lainnya' => 'bg-gray-100 text-gray-700',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex px-2.5 py-1 rounded-lg text-xs font-medium {{ $statusColors[$item->status_sekarang] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $item->status_sekarang }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $item->nama_institusi ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">{{ $item->no_telepon ?? '-' }}</div>
                                @if ($item->email)
                                    <div class="text-xs text-gray-400">{{ $item->email }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $item->created_at?->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="openDetail({{ $item->id }})"
                                        class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors"
                                        title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button wire:click="openDeleteModal({{ $item->id }})"
                                        class="p-2 rounded-lg hover:bg-red-50 text-gray-500 hover:text-red-600 transition-colors"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-sm">Belum ada data tracer alumni.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($data->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $data->links() }}
            </div>
        @endif
    </div>

    {{-- Detail Modal --}}
    @if ($showDetailModal && $selectedData)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeDetail"></div>
                <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Detail Tracer Alumni</h3>
                        <button wire:click="closeDetail" class="p-2 rounded-lg hover:bg-gray-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        {{-- Data Pribadi --}}
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">
                                Data Pribadi</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <span class="text-xs text-gray-500">Nama</span>
                                    <p class="text-sm font-medium">{{ $selectedData->nama_lengkap }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">NISN</span>
                                    <p class="text-sm">{{ $selectedData->nisn ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Jenjang</span>
                                    <p class="text-sm">{{ $selectedData->jenjang }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Tahun Lulus</span>
                                    <p class="text-sm">{{ $selectedData->tahun_lulus }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Jenis Kelamin</span>
                                    <p class="text-sm">
                                        {{ $selectedData->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">TTL</span>
                                    <p class="text-sm">
                                        {{ $selectedData->tempat_lahir ?? '-' }}{{ $selectedData->tanggal_lahir ? ', ' . $selectedData->tanggal_lahir->format('d/m/Y') : '' }}
                                    </p>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-xs text-gray-500">Alamat</span>
                                    <p class="text-sm">{{ $selectedData->alamat ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">No. Telepon</span>
                                    <p class="text-sm">{{ $selectedData->no_telepon ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Email</span>
                                    <p class="text-sm">{{ $selectedData->email ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Status Setelah Lulus --}}
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">
                                Status Setelah Lulus</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <span class="text-xs text-gray-500">Status</span>
                                    <p class="text-sm font-medium">{{ $selectedData->status_sekarang }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Institusi</span>
                                    <p class="text-sm">{{ $selectedData->nama_institusi ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Jurusan/Bidang</span>
                                    <p class="text-sm">{{ $selectedData->jurusan_bidang ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Tahun Masuk</span>
                                    <p class="text-sm">{{ $selectedData->tahun_masuk ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Feedback --}}
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">
                                Feedback</h4>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-xs text-gray-500">Kepuasan Pendidikan</span>
                                    <div class="flex items-center gap-1 mt-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $selectedData->kepuasan_pendidikan && $i <= $selectedData->kepuasan_pendidikan ? 'text-yellow-400' : 'text-gray-200' }}"
                                                fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                            </svg>
                                        @endfor
                                        <span class="text-sm text-gray-500 ml-2">
                                            {{ $selectedData->kepuasan_pendidikan ? $selectedData->kepuasan_pendidikan . '/5' : 'Tidak diisi' }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Kesan & Pesan</span>
                                    <p class="text-sm bg-gray-50 p-3 rounded-lg mt-1">
                                        {{ $selectedData->kesan_pesan ?? '-' }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <span class="text-xs text-gray-500">Sumber Info</span>
                                        <p class="text-sm">{{ $selectedData->sumber_info ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500">Bersedia Dihubungi</span>
                                        <p class="text-sm">
                                            {{ $selectedData->bersedia_dihubungi ? 'Ya' : 'Tidak' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200 text-xs text-gray-400">
                            Dikirim pada: {{ $selectedData->created_at?->format('d F Y, H:i') }}
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button wire:click="closeDetail"
                            class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeDeleteModal"></div>
                <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus Data</h3>
                            <p class="text-sm text-gray-600">
                                Apakah Anda yakin ingin menghapus data tracer alumni ini? Tindakan ini tidak dapat
                                dibatalkan.
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-2">
                        <button wire:click="closeDeleteModal"
                            class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium">
                            Batal
                        </button>
                        <button wire:click="delete"
                            class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-medium">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>