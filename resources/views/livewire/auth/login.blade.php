<div class="animate-fade-up">
    <div class="mb-8 text-center">
        <div class="w-16 h-16 rounded-2xl bg-emerald-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-[hsl(var(--foreground))]">Data Induk Sekolah</h2>
        <p class="text-[hsl(var(--muted-foreground))] mt-1">Masuk untuk mengelola data sekolah</p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1.5">
                Email
            </label>
            <input wire:model="email" id="email" type="email" class="input w-full"
                placeholder="email@sekolah.sch.id" required autofocus autocomplete="username" />
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium text-[hsl(var(--foreground))]">
                    Password
                </label>
                <a class="text-sm text-[hsl(var(--muted-foreground))] hover:text-[hsl(var(--foreground))] transition-colors"
                    href="{{ route('password.request') }}" wire:navigate>
                    Lupa password?
                </a>
            </div>
            <input wire:model="password" id="password" type="password" class="input w-full"
                placeholder="Masukkan password" required autocomplete="current-password" />
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input wire:model="remember" id="remember" type="checkbox"
                class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-600">
            <label for="remember" class="ms-2 text-sm text-[hsl(var(--muted-foreground))]">
                Ingat saya
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-full">
            <svg wire:loading wire:target="login" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            Masuk
        </button>
    </form>

    <!-- Footer -->
    <p class="mt-6 text-center text-xs text-[hsl(var(--muted-foreground))]">
        © {{ date('Y') }} Data Induk Sekolah
    </p>
</div>
