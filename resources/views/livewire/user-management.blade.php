<div>
    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mb-4 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row justify-between gap-3 mb-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Manajemen User</h2>
            <p class="text-sm text-gray-500">Kelola akun admin dan guru yang bisa login ke sistem.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama/email..."
                    class="pl-10 pr-4 py-2 rounded-xl border border-gray-200 bg-white focus:ring-2 focus:ring-gray-900 focus:border-transparent w-64 text-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <button wire:click="openCreate"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
                </svg>
                Tambah User
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Dibuat</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $idx => $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-600">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $idx + 1 }}
                            </td>
                            <td class="px-4 py-3 text-gray-900 font-medium">
                                {{ $user->name }}
                                @if ($user->id === auth()->user()->id)
                                    <span class="ml-1 text-xs text-gray-500">(Anda)</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                @if ($user->role === 'admin')
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        Administrator
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        Guru
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">
                                {{ $user->created_at?->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openEdit({{ $user->id }})"
                                        class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-medium">
                                        Edit
                                    </button>
                                    @if ($user->id !== auth()->user()->id)
                                        <button wire:click="confirmDelete({{ $user->id }})"
                                            class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 text-xs font-medium">
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                Tidak ada user ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl">
                        <form wire:submit.prevent="save">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $editingId ? 'Edit User' : 'Tambah User' }}
                                </h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="name" placeholder="contoh: Pak Budi"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    @error('name')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" wire:model="email" placeholder="contoh: budi@madrasah.sch.id"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    @error('email')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Password
                                        @if ($editingId)
                                            <span class="text-xs text-gray-500 font-normal">(kosongkan jika tidak diubah)</span>
                                        @else
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                    <input type="password" wire:model="password"
                                        placeholder="{{ $editingId ? '••••••••' : 'Minimal 8 karakter' }}"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm">
                                    @error('password')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Role <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="role"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-gray-900 focus:border-transparent text-sm bg-white">
                                        <option value="guru">Guru (hanya akses Nilai Ijazah Kelas 6)</option>
                                        <option value="admin">Administrator (akses penuh)</option>
                                    </select>
                                    @error('role')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="border-t border-gray-200 px-6 py-4 flex justify-end gap-2">
                                <button type="button" wire:click="$set('showModal', false)"
                                    class="px-4 py-2 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-100">
                                    Batal
                                </button>
                                <button type="submit" wire:loading.attr="disabled"
                                    class="px-4 py-2 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium disabled:opacity-60">
                                    <span wire:loading.remove wire:target="save">
                                        {{ $editingId ? 'Simpan Perubahan' : 'Simpan' }}
                                    </span>
                                    <span wire:loading wire:target="save">Menyimpan...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if ($showDeleteModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="$set('showDeleteModal', false)"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl">
                        <div class="px-6 py-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Hapus User?</h3>
                                    <p class="text-sm text-gray-500">Tindakan ini tidak bisa dibatalkan.</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-5">
                                User <strong>{{ $deletingName }}</strong> akan dihapus permanen.
                            </p>
                            <div class="flex justify-end gap-2">
                                <button type="button" wire:click="$set('showDeleteModal', false)"
                                    class="px-4 py-2 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-100">
                                    Batal
                                </button>
                                <button type="button" wire:click="delete" wire:loading.attr="disabled"
                                    class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-medium disabled:opacity-60">
                                    <span wire:loading.remove wire:target="delete">Hapus</span>
                                    <span wire:loading wire:target="delete">Menghapus...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
