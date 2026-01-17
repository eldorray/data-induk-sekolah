<div class="animate-fade-up">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-[hsl(var(--foreground))]">Forgot password?</h2>
        <p class="text-[hsl(var(--muted-foreground))] mt-1">
            No problem. Enter your email and we'll send you a password reset link.
        </p>
    </div>

    @if ($emailSent)
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
            We have emailed your password reset link. Please check your inbox.
        </div>
    @endif

    <form wire:submit="sendResetLink" class="space-y-5">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1.5">
                Email
            </label>
            <input wire:model="email" 
                   id="email" 
                   type="email" 
                   class="input w-full" 
                   placeholder="you@example.com"
                   required 
                   autofocus 
                   autocomplete="username" />
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-full">
            <svg wire:loading wire:target="sendResetLink" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Email Password Reset Link
        </button>
    </form>

    <!-- Back to Login -->
    <p class="mt-6 text-center text-sm text-[hsl(var(--muted-foreground))]">
        Remember your password?
        <a href="{{ route('login') }}" class="font-medium text-[hsl(var(--foreground))] hover:underline" wire:navigate>
            Sign in
        </a>
    </p>
</div>
