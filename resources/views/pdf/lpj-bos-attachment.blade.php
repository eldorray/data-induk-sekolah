<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lampiran LPJ BOS</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; margin: 0; padding: 16px; }
        h2 { margin: 0 0 4px; font-size: 14px; }
        .muted { color: #555; margin: 0 0 10px; }
        .attachment-image { width: 100%; max-height: 950px; object-fit: contain; }
    </style>
</head>
<body>
    <h2>{{ ucfirst($attachment->kategori) }} — {{ $attachment->original_name }}</h2>
    @if ($attachment->keterangan)
        <p class="muted">{{ $attachment->keterangan }}</p>
    @endif
    @php $absolutePath = storage_path('app/public/'.$attachment->file_path); @endphp
    @if (file_exists($absolutePath))
        <img src="{{ $absolutePath }}" class="attachment-image" alt="{{ $attachment->original_name }}">
    @else
        <p>File gambar tidak ditemukan di storage.</p>
    @endif
</body>
</html>
