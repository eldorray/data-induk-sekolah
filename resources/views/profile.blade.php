<x-layouts.admin>
    <x-slot name="header">
        Profile
    </x-slot>

    <div class="animate-fade-up">
        <div class="card p-8 max-w-2xl">
            <h3 class="text-xl font-semibold text-[hsl(var(--foreground))] mb-6">Profile Information</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-[hsl(var(--muted-foreground))] mb-1">Name</label>
                    <p class="text-[hsl(var(--foreground))]">{{ auth()->user()->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-[hsl(var(--muted-foreground))] mb-1">Email</label>
                    <p class="text-[hsl(var(--foreground))]">{{ auth()->user()->email }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-[hsl(var(--muted-foreground))] mb-1">Member Since</label>
                    <p class="text-[hsl(var(--foreground))]">{{ auth()->user()->created_at->format('F j, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
