<div class="animate-fade-up">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-[hsl(var(--foreground))]">Reset password</h2>
        <p class="text-[hsl(var(--muted-foreground))] mt-1">
            Enter your new password below.
        </p>
    </div>

    <form wire:submit="resetPassword" class="space-y-5">
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
                   autocomplete="username" />
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1.5">
                New Password
            </label>
            <input wire:model="password" 
                   id="password" 
                   type="password" 
                   class="input w-full"
                   placeholder="Enter new password"
                   required 
                   autocomplete="new-password" />
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1.5">
                Confirm New Password
            </label>
            <input wire:model="password_confirmation" 
                   id="password_confirmation" 
                   type="password" 
                   class="input w-full"
                   placeholder="Confirm new password"
                   required 
                   autocomplete="new-password" />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-full">
            <svg wire:loading wire:target="resetPassword" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Reset Password
        </button>
    </form>
</div>
