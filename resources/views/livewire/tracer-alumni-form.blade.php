<div>
    <!-- Navigation -->
    <nav class="nav-apple" x-data="{ scrolled: false }" @scroll.window="scrolled = window.scrollY > 50"
        :class="{ 'scrolled': scrolled }">
        <div class="container-tight">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-2 font-semibold text-[hsl(var(--foreground))]">
                    <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="hidden sm:inline">Data Induk Sekolah</span>
                </a>
                <a href="/" class="btn btn-primary btn-sm">
                    ← Kembali
                </a>
            </div>
        </div>
    </nav>

    <div class="section-padding">
        <div class="container-tight max-w-3xl mx-auto">
            @if ($submitted)
                <!-- Success State -->
                <div class="card p-8 text-center animate-fade-up">
                    <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold mb-3">Terima Kasih!</h2>
                    <p class="text-[hsl(var(--muted-foreground))] mb-6">
                        Data tracer alumni Anda telah berhasil dikirim. Informasi ini sangat berharga bagi kami untuk
                        meningkatkan kualitas pendidikan.
                    </p>
                    <button wire:click="resetForm" class="btn btn-primary">
                        Isi Form Lagi
                    </button>
                </div>
            @else
                <!-- Form Header -->
                <div class="text-center mb-8 animate-fade-up">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-100 text-sm font-medium text-emerald-800 mb-4">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                        </svg>
                        Tracer Alumni
                    </div>
                    <h1 class="text-3xl font-bold mb-3">Form Tracer Alumni</h1>
                    <p class="text-[hsl(var(--muted-foreground))] max-w-xl mx-auto">
                        Bantu kami mengetahui perkembangan alumni MI & SMP. Data ini digunakan untuk evaluasi dan
                        peningkatan kualitas pendidikan.
                    </p>
                </div>

                <!-- Form -->
                <form wire:submit="submit" class="space-y-8 animate-fade-up">
                    <!-- Section: Data Pribadi -->
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Data Pribadi
                        </h3>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="nama_lengkap"
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nama_lengkap" wire:model="nama_lengkap"
                                    class="input w-full" placeholder="Masukkan nama lengkap">
                                @error('nama_lengkap')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nisn"
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1">
                                    NISN
                                </label>
                                <input type="text" id="nisn" wire:model="nisn" class="input w-full"
                                    placeholder="Nomor Induk Siswa Nasional">
                                @error('nisn')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="jenjang"
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1">
                                    Jenjang <span class="text-red-500">*</span>
                                </label>
                                <select id="jenjang" wire:model="jenjang" class="input w-full">
                                    <option value="">Pilih Jenjang</option>
                                    <option value="MI">MI (Madrasah Ibtidaiyah)</option>
                                    <option value="SMP">SMP (Sekolah Menengah Pertama)</option>
                                </select>
                                @error('jenjang')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tahun_lulus"
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1">
                                    Tahun Lulus <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="tahun_lulus" wire:model="tahun_lulus" class="input w-full"
                                    placeholder="Contoh: 2020" maxlength="4">
                                @error('tahun_lulus')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="jenis_kelamin"
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select id="jenis_kelamin" wire:model="jenis_kelamin" class="input w-full">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tempat_lahir"
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1">
                                    Tempat Lahir
                                </label>
                                <input type="text" id="tempat_lahir" wire:model="tempat_lahir"
                                    class="input w-full" placeholder="Kota/Kabupaten">
                                @error('tempat_lahir')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_lahir"
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1">
                                    Tanggal Lahir
                                </label>
                                <input type="date" id="tanggal_lahir" wire:model="tanggal_lahir"
                                    class="input w-full">
                                @error('tanggal_lahir')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="alamat"
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1">
                                    Alamat
                                </label>
                                <textarea id="alamat" wire:model="alamat" class="input w-full" rows="2"
                                    placeholder="Alamat lengkap saat ini"></textarea>
                                @error('alamat')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="no_telepon"
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1">
                                    No. Telepon / WhatsApp
                                </label>
                                <input type="text" id="no_telepon" wire:model="no_telepon" class="input w-full"
                                    placeholder="08xxxxxxxxxx">
                                @error('no_telepon')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email"
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1">
                                    Email
                                </label>
                                <input type="email" id="email" wire:model="email" class="input w-full"
                                    placeholder="email@contoh.com">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section: Status Setelah Lulus -->
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Status Setelah Lulus
                        </h3>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="status_sekarang"
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1">
                                    Status Saat Ini <span class="text-red-500">*</span>
                                </label>
                                <select id="status_sekarang" wire:model="status_sekarang" class="input w-full">
                                    <option value="">Pilih Status</option>
                                    <option value="Kuliah">Kuliah</option>
                                    <option value="Bekerja">Bekerja</option>
                                    <option value="Wirausaha">Wirausaha</option>
                                    <option value="Belum Bekerja">Belum Bekerja</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                @error('status_sekarang')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nama_institusi"
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1">
                                    Nama Institusi / Perusahaan / Usaha
                                </label>
                                <input type="text" id="nama_institusi" wire:model="nama_institusi"
                                    class="input w-full" placeholder="Nama tempat kuliah/kerja/usaha">
                                @error('nama_institusi')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="jurusan_bidang"
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1">
                                    Jurusan / Bidang
                                </label>
                                <input type="text" id="jurusan_bidang" wire:model="jurusan_bidang"
                                    class="input w-full" placeholder="Jurusan kuliah / bidang pekerjaan">
                                @error('jurusan_bidang')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tahun_masuk"
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1">
                                    Tahun Masuk
                                </label>
                                <input type="text" id="tahun_masuk" wire:model="tahun_masuk"
                                    class="input w-full" placeholder="Contoh: 2021" maxlength="4">
                                @error('tahun_masuk')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section: Feedback -->
                    <div class="card p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Feedback & Kesan
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-2">
                                    Tingkat Kepuasan terhadap Pendidikan di Sekolah
                                </label>
                                <div class="flex items-center gap-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button type="button"
                                            wire:click="$set('kepuasan_pendidikan', {{ $i }})"
                                            class="w-10 h-10 rounded-lg border-2 flex items-center justify-center text-sm font-semibold transition-all {{ $kepuasan_pendidikan === $i ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-[hsl(var(--border))] hover:border-emerald-300' }}">
                                            {{ $i }}
                                        </button>
                                    @endfor
                                    <span class="text-sm text-[hsl(var(--muted-foreground))] ml-2">
                                        (1 = Kurang, 5 = Sangat Baik)
                                    </span>
                                </div>
                                @error('kepuasan_pendidikan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="kesan_pesan"
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1">
                                    Kesan & Pesan untuk Sekolah
                                </label>
                                <textarea id="kesan_pesan" wire:model="kesan_pesan" class="input w-full" rows="4"
                                    placeholder="Tuliskan kesan, pesan, atau saran Anda untuk sekolah..."></textarea>
                                @error('kesan_pesan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="sumber_info"
                                    class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1">
                                    Dari mana Anda mengetahui form ini?
                                </label>
                                <select id="sumber_info" wire:model="sumber_info" class="input w-full">
                                    <option value="">Pilih Sumber</option>
                                    <option value="Website Sekolah">Website Sekolah</option>
                                    <option value="WhatsApp">WhatsApp</option>
                                    <option value="Media Sosial">Media Sosial</option>
                                    <option value="Teman/Alumni Lain">Teman/Alumni Lain</option>
                                    <option value="Guru/Sekolah">Guru/Sekolah</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                @error('sumber_info')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="bersedia_dihubungi" wire:model="bersedia_dihubungi"
                                    class="w-4 h-4 rounded border-[hsl(var(--border))] text-emerald-600 focus:ring-emerald-500">
                                <label for="bersedia_dihubungi"
                                    class="text-sm text-[hsl(var(--foreground))]">
                                    Saya bersedia dihubungi oleh pihak sekolah
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="btn btn-primary btn-lg" wire:loading.attr="disabled">
                            <span wire:loading.remove>Kirim Data</span>
                            <span wire:loading>Mengirim...</span>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>