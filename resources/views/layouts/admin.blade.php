<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Admin - {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Alpine.js from CDN (ensures it loads before body) -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles

        <style>
            /* Apple-style animations */
            .apple-transition {
                transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            }
            
            .apple-spring {
                transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            
            .apple-ease {
                transition: all 0.4s cubic-bezier(0.25, 0.1, 0.25, 1);
            }
            
            .sidebar-item {
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }
            
            .sidebar-item:hover {
                transform: translateX(4px);
            }
            
            .sidebar-item:active {
                transform: scale(0.98);
            }
            
            .toggle-btn {
                transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            
            .toggle-btn:hover {
                transform: scale(1.1);
                background-color: #f3f4f6;
            }
            
            .toggle-btn:active {
                transform: scale(0.95);
            }
            
            .fade-slide-enter {
                animation: fadeSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            
            @keyframes fadeSlideIn {
                from {
                    opacity: 0;
                    transform: translateX(-8px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            .icon-bounce {
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            
            .icon-bounce:hover {
                transform: scale(1.15);
            }
            
            /* Smooth backdrop blur */
            .glass-header {
                backdrop-filter: saturate(180%) blur(20px);
                -webkit-backdrop-filter: saturate(180%) blur(20px);
            }
            
            /* Active nav indicator */
            .nav-active {
                box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.2);
            }
            
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div x-data="{ sidebarOpen: false, sidebarCollapsed: false }" class="min-h-screen">
            <!-- Mobile Sidebar Overlay -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 lg:hidden"
                 @click="sidebarOpen = false"
                 x-cloak>
            </div>

            <!-- Sidebar -->
            <aside x-cloak
                   class="fixed inset-y-0 left-0 z-50 bg-white/95 backdrop-blur-xl border-r border-gray-200/80 apple-transition lg:translate-x-0"
                   :class="{
                       '-translate-x-full': !sidebarOpen,
                       'translate-x-0': sidebarOpen,
                       'w-64': !sidebarCollapsed,
                       'w-[72px]': sidebarCollapsed
                   }">
                <div class="flex flex-col h-full">
                    <!-- Logo -->
                    <div class="flex items-center h-16 px-4 border-b border-gray-200/80 apple-ease"
                         :class="{ 'justify-center': sidebarCollapsed, 'gap-3': !sidebarCollapsed }">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center flex-shrink-0 shadow-lg icon-bounce">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span x-show="!sidebarCollapsed" 
                              x-transition:enter="transition-all duration-300 ease-out"
                              x-transition:enter-start="opacity-0 -translate-x-2"
                              x-transition:enter-end="opacity-100 translate-x-0"
                              x-transition:leave="transition-all duration-200 ease-in"
                              x-transition:leave-start="opacity-100"
                              x-transition:leave-end="opacity-0 -translate-x-2"
                              class="font-semibold text-gray-900 whitespace-nowrap">Admin Panel</span>
                    </div>

                    <!-- Collapse Toggle Button (Desktop only) -->
                    <div class="hidden lg:flex items-center px-3 py-3 border-b border-gray-200/80"
                         :class="{ 'justify-center': sidebarCollapsed, 'justify-end': !sidebarCollapsed }">
                        <button @click="sidebarCollapsed = !sidebarCollapsed" 
                                class="toggle-btn p-2.5 rounded-xl text-gray-400 hover:text-gray-600"
                                :title="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'">
                            <svg class="w-5 h-5 apple-spring" 
                                 :class="{ 'rotate-180': sidebarCollapsed }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Close button for mobile -->
                    <button @click="sidebarOpen = false" 
                            class="lg:hidden absolute top-4 right-4 p-2 rounded-lg hover:bg-gray-100 text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <!-- Navigation -->
                    <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto">
                        <a href="{{ route('dashboard') }}" 
                           class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white nav-active' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
                           :class="{ 'justify-center px-0': sidebarCollapsed }"
                           wire:navigate
                           title="Dashboard">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 {{ request()->routeIs('dashboard') ? 'bg-white/20' : 'bg-gray-100' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                            </div>
                            <span x-show="!sidebarCollapsed" 
                                  x-transition:enter="transition-all duration-300 ease-out delay-75"
                                  x-transition:enter-start="opacity-0"
                                  x-transition:enter-end="opacity-100"
                                  x-transition:leave="transition-all duration-150 ease-in"
                                  x-transition:leave-start="opacity-100"
                                  x-transition:leave-end="opacity-0"
                                  class="whitespace-nowrap">Dashboard</span>
                        </a>

                        <a href="{{ route('profile') }}" 
                           class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium {{ request()->routeIs('profile') ? 'bg-gray-900 text-white nav-active' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
                           :class="{ 'justify-center px-0': sidebarCollapsed }"
                           wire:navigate
                           title="Profile">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 {{ request()->routeIs('profile') ? 'bg-white/20' : 'bg-gray-100' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span x-show="!sidebarCollapsed"
                                  x-transition:enter="transition-all duration-300 ease-out delay-100"
                                  x-transition:enter-start="opacity-0"
                                  x-transition:enter-end="opacity-100"
                                  x-transition:leave="transition-all duration-150 ease-in"
                                  x-transition:leave-start="opacity-100"
                                  x-transition:leave-end="opacity-0"
                                  class="whitespace-nowrap">Profile</span>
                        </a>
                    </nav>

                    <!-- User Menu -->
                    <div class="px-3 py-4 border-t border-gray-200/80">
                        <div class="flex items-center gap-3 px-3 py-2 mb-2 apple-ease"
                             :class="{ 'justify-center px-0': sidebarCollapsed }">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center flex-shrink-0 shadow-md icon-bounce">
                                <span class="text-sm font-bold text-white">
                                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                </span>
                            </div>
                            <div x-show="!sidebarCollapsed" 
                                 x-transition:enter="transition-all duration-300 ease-out"
                                 x-transition:enter-start="opacity-0 -translate-x-2"
                                 x-transition:enter-end="opacity-100 translate-x-0"
                                 x-transition:leave="transition-all duration-200 ease-in"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">
                                    {{ auth()->user()->name ?? 'User' }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">
                                    {{ auth()->user()->email ?? '' }}
                                </p>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <a href="/" target="_blank" 
                               class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900"
                               :class="{ 'justify-center px-0': sidebarCollapsed }"
                               title="View Site">
                                <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </div>
                                <span x-show="!sidebarCollapsed"
                                      x-transition:enter="transition-all duration-300 ease-out"
                                      x-transition:enter-start="opacity-0"
                                      x-transition:enter-end="opacity-100"
                                      x-transition:leave="transition-all duration-150 ease-in"
                                      x-transition:leave-start="opacity-100"
                                      x-transition:leave-end="opacity-0"
                                      class="whitespace-nowrap font-medium">View Site</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-red-600 hover:bg-red-50 hover:text-red-700 w-full"
                                        :class="{ 'justify-center px-0': sidebarCollapsed }"
                                        title="Logout">
                                    <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                    </div>
                                    <span x-show="!sidebarCollapsed"
                                          x-transition:enter="transition-all duration-300 ease-out"
                                          x-transition:enter-start="opacity-0"
                                          x-transition:enter-end="opacity-100"
                                          x-transition:leave="transition-all duration-150 ease-in"
                                          x-transition:leave-start="opacity-100"
                                          x-transition:leave-end="opacity-0"
                                          class="whitespace-nowrap font-medium">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="apple-transition lg:translate-x-0"
                 :class="{ 'lg:pl-64': !sidebarCollapsed, 'lg:pl-[72px]': sidebarCollapsed }">
                <!-- Top Header -->
                <header class="glass-header sticky top-0 z-30 flex items-center justify-between h-16 px-6 border-b border-gray-200/80 bg-white/70">
                    <!-- Mobile Menu Button -->
                    <button type="button" 
                            @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden p-2.5 -ml-2 rounded-xl hover:bg-gray-100 text-gray-600 apple-ease">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- Page Title -->
                    @isset($header)
                        <h1 class="text-lg font-semibold text-gray-900">
                            {{ $header }}
                        </h1>
                    @else
                        <div></div>
                    @endisset

                    <!-- Right Side -->
                    <div class="flex items-center gap-3">
                        <a href="/" target="_blank" class="hidden sm:flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 apple-ease">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            View Site
                        </a>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="p-6">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 fade-slide-enter">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 fade-slide-enter">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
