@php
    $faviconPath = \App\Models\SchoolSetting::get('favicon_path');
@endphp

@if ($faviconPath)
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $faviconPath) }}">
    <link rel="shortcut icon" href="{{ asset('storage/' . $faviconPath) }}">
@else
    <link rel="icon"
        href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22><rect width=%2224%22 height=%2224%22 rx=%224%22 fill=%22%23059669%22/><path d=%22M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253%22 stroke=%22white%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22 fill=%22none%22/></svg>">
@endif