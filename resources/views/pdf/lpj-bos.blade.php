<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LPJ BOS</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        h1, h2 { margin: 0 0 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        td, th { border: 1px solid #333; padding: 6px; vertical-align: top; }
        .no-border td { border: none; padding: 3px; }
        .page-break { page-break-before: always; }
        .attachment-image { width: 100%; max-height: 900px; object-fit: contain; }
        .muted { color: #555; }
    </style>
</head>
<body>
    <h1>Laporan Pertanggungjawaban BOS</h1>
    <table class="no-border">
        <tr><td style="width: 160px">Nomor Bukti</td><td>: {{ $lpj->kuitansi->nomor_bukti_lengkap }}</td></tr>
        <tr><td>Tahun Anggaran</td><td>: {{ $lpj->kuitansi->tahun_anggaran }}</td></tr>
        <tr><td>Penerima</td><td>: {{ $lpj->kuitansi->penerima }}</td></tr>
        <tr><td>Jumlah</td><td>: {{ $lpj->kuitansi->jumlah_format }}</td></tr>
        <tr><td>Uraian Pembayaran</td><td>: {{ $lpj->kuitansi->uraian_pembayaran }}</td></tr>
        <tr><td>Nama Kegiatan</td><td>: {{ $lpj->nama_kegiatan }}</td></tr>
        <tr><td>Tanggal Kegiatan</td><td>: {{ $lpj->tanggal_kegiatan->format('d-m-Y') }}</td></tr>
        <tr><td>Lokasi</td><td>: {{ $lpj->lokasi }}</td></tr>
        <tr><td>Status</td><td>: {{ $lpj->completeness_label }}</td></tr>
        <tr><td>Catatan</td><td>: {{ $lpj->catatan ?: '-' }}</td></tr>
    </table>

    <h2>Daftar Lampiran PDF</h2>
    <table>
        <thead><tr><th>Kategori</th><th>Nama File</th><th>Keterangan</th></tr></thead>
        <tbody>
            @forelse ($pdfAttachments as $attachment)
                <tr>
                    <td>{{ ucfirst($attachment->kategori) }}</td>
                    <td>{{ $attachment->original_name }}</td>
                    <td>{{ $attachment->keterangan ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Tidak ada lampiran PDF.</td></tr>
            @endforelse
        </tbody>
    </table>

    @foreach ($imageAttachments as $attachment)
        <div class="page-break">
            <h2>{{ ucfirst($attachment->kategori) }} — {{ $attachment->original_name }}</h2>
            @if ($attachment->keterangan)
                <p class="muted">{{ $attachment->keterangan }}</p>
            @endif
            @php $absolutePath = storage_path('app/public/' . $attachment->file_path); @endphp
            @if (file_exists($absolutePath))
                <img src="{{ $absolutePath }}" class="attachment-image" alt="{{ $attachment->original_name }}">
            @else
                <p>File gambar tidak ditemukan di storage.</p>
            @endif
        </div>
    @endforeach
</body>
</html>
