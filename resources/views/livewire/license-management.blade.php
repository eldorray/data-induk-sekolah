<div class="animate-fade-up">
    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Generated Key Alert --}}
    @if ($generatedKey)
        <div class="mb-6 p-6 rounded-2xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200"
            x-data="{ copied: false }">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-emerald-800 mb-1">🎉 License Key Berhasil Dibuat!</h3>
                    <p class="text-xs text-emerald-600 mb-3">Salin key berikut dan berikan ke admin e-raport client.</p>
                </div>
                <button wire:click="dismissGeneratedKey"
                    class="text-emerald-400 hover:text-emerald-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="flex items-center gap-3">
                <code
                    class="flex-1 px-4 py-3 rounded-xl bg-white border border-emerald-200 text-lg font-mono font-bold text-emerald-900 tracking-wider text-center select-all">
                    {{ $generatedKey }}
                </code>
                <button
                    @click="navigator.clipboard.writeText('{{ $generatedKey }}'); copied = true; setTimeout(() => copied = false, 2000)"
                    class="px-4 py-3 rounded-xl text-sm font-medium transition-colors"
                    :class="copied ? 'bg-emerald-600 text-white' :
                        'bg-white border border-emerald-200 text-emerald-700 hover:bg-emerald-50'">
                    <span x-show="!copied" class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3">
                            </path>
                        </svg>
                        Copy
                    </span>
                    <span x-show="copied" x-cloak class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Copied!
                    </span>
                </button>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Generate Form --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden sticky top-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-900">Generate License Baru</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sekolah
                            <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="school_name" placeholder="MI/SMP Al Madani..."
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                        @error('school_name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Berlaku Sampai
                            <span class="text-gray-400 text-xs">(opsional)</span></label>
                        <input type="date" wire:model="expires_at"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                        <p class="text-xs text-gray-400 mt-1">Kosongkan jika berlaku selamanya</p>
                        @error('expires_at')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan
                            <span class="text-gray-400 text-xs">(opsional)</span></label>
                        <textarea wire:model="notes" rows="2" placeholder="Catatan tambahan..."
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm"></textarea>
                    </div>

                    <button wire:click="generateLicense" wire:loading.attr="disabled"
                        class="w-full px-4 py-3 rounded-xl bg-gray-900 hover:bg-gray-800 disabled:bg-gray-400 text-white text-sm font-medium flex items-center justify-center gap-2 transition-colors">
                        <svg wire:loading.remove wire:target="generateLicense" class="w-4 h-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                            </path>
                        </svg>
                        <svg wire:loading wire:target="generateLicense" class="w-4 h-4 animate-spin" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Generate License Key
                    </button>
                </div>
            </div>
        </div>

        {{-- Right: License List --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <h3 class="text-sm font-semibold text-gray-900">Daftar License
                            ({{ $licenses->total() }})</h3>
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Cari sekolah atau key..."
                                class="pl-8 pr-4 py-1.5 rounded-lg border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent w-56 text-sm">
                            <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    License Key</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Sekolah</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Domain</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Berlaku</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($licenses as $license)
                                <tr class="hover:bg-gray-50 transition-colors" x-data="{ copied: false }">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <code
                                                class="text-sm font-mono font-medium text-gray-900">{{ $license->license_key }}</code>
                                            <button
                                                @click="navigator.clipboard.writeText('{{ $license->license_key }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                                class="p-1 rounded-md hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors"
                                                title="Copy key">
                                                <svg x-show="!copied" class="w-3.5 h-3.5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3">
                                                    </path>
                                                </svg>
                                                <svg x-show="copied" x-cloak class="w-3.5 h-3.5 text-green-600"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $license->school_name }}
                                        </div>
                                        @if ($license->notes)
                                            <div class="text-xs text-gray-500 truncate max-w-[150px]">
                                                {{ $license->notes }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($license->domain)
                                            <span class="text-sm text-gray-600">{{ $license->domain }}</span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum diaktivasi</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $color = $license->status_color;
                                        @endphp
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                                            {{ $color === 'green' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $color === 'yellow' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $color === 'red' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $color === 'gray' ? 'bg-gray-100 text-gray-800' : '' }}">
                                            <span
                                                class="w-1.5 h-1.5 rounded-full
                                                {{ $color === 'green' ? 'bg-green-500' : '' }}
                                                {{ $color === 'yellow' ? 'bg-yellow-500' : '' }}
                                                {{ $color === 'red' ? 'bg-red-500' : '' }}
                                                {{ $color === 'gray' ? 'bg-gray-500' : '' }}"></span>
                                            {{ ucfirst($license->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $license->expires_label }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            @if ($license->status === 'active')
                                                <button wire:click="openRevokeModal({{ $license->id }})"
                                                    class="p-2 rounded-lg hover:bg-yellow-50 text-gray-500 hover:text-yellow-600 transition-colors"
                                                    title="Cabut Lisensi">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                                        </path>
                                                    </svg>
                                                </button>
                                            @elseif($license->status === 'revoked')
                                                <button wire:click="reactivate({{ $license->id }})"
                                                    class="p-2 rounded-lg hover:bg-green-50 text-gray-500 hover:text-green-600 transition-colors"
                                                    title="Aktifkan Kembali">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                        </path>
                                                    </svg>
                                                </button>
                                            @endif
                                            <button wire:click="openDeleteModal({{ $license->id }})"
                                                class="p-2 rounded-lg hover:bg-red-50 text-gray-500 hover:text-red-600 transition-colors"
                                                title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                            </path>
                                        </svg>
                                        <p class="text-sm">Belum ada license key. Generate yang pertama!</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($licenses->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $licenses->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Revoke Confirmation Modal --}}
    @if ($showRevokeModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl">
                        <div class="p-6 text-center">
                            <svg class="w-16 h-16 mx-auto text-yellow-500 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Cabut License Key?</h3>
                            <p class="text-gray-500 mb-6">Aplikasi e-raport yang menggunakan license ini tidak akan
                                bisa login lagi. Anda bisa mengaktifkannya kembali nanti.</p>
                            <div class="flex justify-center gap-3">
                                <button wire:click="closeModal"
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">
                                    Batal
                                </button>
                                <button wire:click="revoke"
                                    class="px-4 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium transition-colors">
                                    Ya, Cabut
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($showDeleteModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl">
                        <div class="p-6 text-center">
                            <svg class="w-16 h-16 mx-auto text-red-500 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus License Key?</h3>
                            <p class="text-gray-500 mb-6">Tindakan ini tidak dapat dibatalkan. License key akan
                                dihapus secara permanen.</p>
                            <div class="flex justify-center gap-3">
                                <button wire:click="closeModal"
                                    class="px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">
                                    Batal
                                </button>
                                <button wire:click="delete"
                                    class="px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-colors">
                                    Ya, Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
