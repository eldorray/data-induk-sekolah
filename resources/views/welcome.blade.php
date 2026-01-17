<x-layouts.app title="Laravel Livewire Starter Kit">
    <!-- Navigation -->
    <nav class="nav-apple" 
         x-data="{ scrolled: false, mobileOpen: false }" 
         @scroll.window="scrolled = window.scrollY > 50"
         :class="{ 'scrolled': scrolled }">
        <div class="container-tight">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 font-semibold text-[hsl(var(--foreground))]">
                    <div class="w-8 h-8 rounded-lg bg-[hsl(var(--primary))] flex items-center justify-center">
                        <svg class="w-5 h-5 text-[hsl(var(--primary-foreground))]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="hidden sm:inline">Livewire Starter</span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-sm font-medium text-[hsl(var(--muted-foreground))] hover:text-[hsl(var(--foreground))] transition-colors">
                        Features
                    </a>
                    <a href="#components" class="text-sm font-medium text-[hsl(var(--muted-foreground))] hover:text-[hsl(var(--foreground))] transition-colors">
                        Components
                    </a>
                    <a href="https://livewire.laravel.com/docs" target="_blank" class="text-sm font-medium text-[hsl(var(--muted-foreground))] hover:text-[hsl(var(--foreground))] transition-colors">
                        Documentation
                    </a>
                </div>

                <!-- Right Side -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm" wire:navigate>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-ghost btn-sm" wire:navigate>
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm hidden sm:flex" wire:navigate>
                            Get Started
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-[hsl(var(--accent))]">
                        <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 x-cloak
                 class="md:hidden py-4 border-t border-[hsl(var(--border))]">
                <div class="flex flex-col gap-4">
                    <a href="#features" class="text-sm font-medium text-[hsl(var(--muted-foreground))] hover:text-[hsl(var(--foreground))]">Features</a>
                    <a href="#components" class="text-sm font-medium text-[hsl(var(--muted-foreground))] hover:text-[hsl(var(--foreground))]">Components</a>
                    <a href="https://livewire.laravel.com/docs" target="_blank" class="text-sm font-medium text-[hsl(var(--muted-foreground))] hover:text-[hsl(var(--foreground))]">Documentation</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero gradient-mesh">
        <div class="hero-content animate-fade-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[hsl(var(--secondary))] text-sm font-medium text-[hsl(var(--secondary-foreground))] mb-6">
                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                Laravel {{ app()->version() }} + Livewire + Breeze
            </div>
            
            <h1 class="hero-title text-balance mb-6">
                Build Dynamic UIs<br>with Pure PHP
            </h1>
            
            <p class="text-lg md:text-xl text-[hsl(var(--muted-foreground))] max-w-2xl mx-auto mb-8">
                Complete starter kit with authentication, admin panel, and beautiful UI components. 
                Start building your next project in minutes.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg" wire:navigate>
                        Go to Dashboard
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg" wire:navigate>
                        Get Started Free
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline btn-lg" wire:navigate>
                        Sign In
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section-padding">
        <div class="container-tight">
            <div class="text-center mb-16 animate-fade-up">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Everything You Need</h2>
                <p class="text-[hsl(var(--muted-foreground))] max-w-2xl mx-auto">
                    This starter kit comes with all the essential features to kickstart your project.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="card p-6 animate-fade-up delay-100">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Authentication</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Complete auth system with Laravel Breeze: login, register, password reset, and email verification.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="card p-6 animate-fade-up delay-200">
                    <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Admin Panel</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Beautiful admin dashboard with sidebar navigation, responsive design, and dark mode support.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="card p-6 animate-fade-up delay-300">
                    <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Livewire Powered</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Build reactive components with Livewire. Real-time updates without writing JavaScript.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="card p-6 animate-fade-up delay-100">
                    <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">UI Components</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Pre-built Blade components inspired by shadcn/ui. Buttons, cards, inputs, badges, and more.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="card p-6 animate-fade-up delay-200">
                    <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Mobile Responsive</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Fully responsive design that works beautifully on desktop, tablet, and mobile devices.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="card p-6 animate-fade-up delay-300">
                    <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Modern Design</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Apple-inspired aesthetics with smooth animations, glassmorphism, and elegant typography.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Components Section -->
    <section id="components" class="section-padding bg-[hsl(var(--secondary))]">
        <div class="container-tight">
            <div class="text-center mb-16 animate-fade-up">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">UI Components</h2>
                <p class="text-[hsl(var(--muted-foreground))] max-w-2xl mx-auto">
                    Ready-to-use Blade components inspired by shadcn/ui for rapid development.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="card-premium p-4 text-center">
                    <code class="text-sm font-mono">x-ui.button</code>
                </div>
                <div class="card-premium p-4 text-center">
                    <code class="text-sm font-mono">x-ui.card</code>
                </div>
                <div class="card-premium p-4 text-center">
                    <code class="text-sm font-mono">x-ui.input</code>
                </div>
                <div class="card-premium p-4 text-center">
                    <code class="text-sm font-mono">x-ui.textarea</code>
                </div>
                <div class="card-premium p-4 text-center">
                    <code class="text-sm font-mono">x-ui.select</code>
                </div>
                <div class="card-premium p-4 text-center">
                    <code class="text-sm font-mono">x-ui.badge</code>
                </div>
                <div class="card-premium p-4 text-center">
                    <code class="text-sm font-mono">x-ui.alert</code>
                </div>
                <div class="card-premium p-4 text-center">
                    <code class="text-sm font-mono">x-layouts.admin</code>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-padding gradient-hero">
        <div class="container-tight text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Build?</h2>
            <p class="text-[hsl(var(--muted-foreground))] max-w-xl mx-auto mb-8">
                Start building your next project with this starter kit. Sign up now and get instant access.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg" wire:navigate>
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg" wire:navigate>
                        Create Free Account
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline btn-lg" wire:navigate>
                        Sign In
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-[hsl(var(--border))] py-12">
        <div class="container-tight">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-[hsl(var(--muted-foreground))]">
                    © {{ date('Y') }} Laravel Livewire Starter Kit. Built with ❤️
                </p>
                <div class="flex items-center gap-6">
                    <a href="https://laravel.com" target="_blank" class="text-sm text-[hsl(var(--muted-foreground))] hover:text-[hsl(var(--foreground))] transition-colors">
                        Laravel
                    </a>
                    <a href="https://livewire.laravel.com" target="_blank" class="text-sm text-[hsl(var(--muted-foreground))] hover:text-[hsl(var(--foreground))] transition-colors">
                        Livewire
                    </a>
                    <a href="https://tailwindcss.com" target="_blank" class="text-sm text-[hsl(var(--muted-foreground))] hover:text-[hsl(var(--foreground))] transition-colors">
                        Tailwind CSS
                    </a>
                </div>
            </div>
        </div>
    </footer>
</x-layouts.app>
