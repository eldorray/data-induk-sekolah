@props([
    'size' => 'md',
    'showFallback' => true,
])

@php
    $appLogoPath = \App\Models\SchoolSetting::get('app_logo_path');
    $sizeClasses = [
        'xs' => 'w-6 h-6',
        'sm' => 'w-8 h-8',
        'md' => 'w-10 h-10',
        'lg' => 'w-16 h-16',
        'xl' => 'w-20 h-20',
    ];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

@if ($appLogoPath)
    <img src="{{ asset('storage/' . $appLogoPath) }}" alt="{{ config('app.name', 'Logo') }}"
        {{ $attributes->merge(['class' => $sizeClass . ' object-contain rounded-lg']) }}>
@elseif ($showFallback)
    <div {{ $attributes->merge(['class' => $sizeClass . ' rounded-lg bg-emerald-600 flex items-center justify-center']) }}>
        <svg class="w-3/5 h-3/5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
    </div>
@endif