<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap LPJ BOS</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111; }
        h1 { margin: 0 0 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td, th { border: 1px solid #333; padding: 5px; vertical-align: top; }
        .summary td { width: 25%; }
    </style>
</head>
<body>
    <h1>Rekap LPJ BOS</h1>

    <table class="summary">
        <tr>
            <td>Total LPJ<br><strong>{{ $summary['total_lpj'] }}</strong></td>
            <td>Lengkap<br><strong>{{ $summary['total_lengkap'] }}</strong></td>
            <td>Belum Lengkap<br><strong>{{ $summary['total_belum_lengkap'] }}</strong></td>
            <td>Total Nominal<br><strong>Rp {{ number_format($summary['total_nominal'], 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <p>
        Filter:
        Tahun {{ $filters['tahun'] ?? 'Semua' }},
        Tanggal {{ $filters['tanggal_awal'] ?? '-' }} s/d {{ $filters['tanggal_akhir'] ?? '-' }},
        Status {{ $filters['kelengkapan'] ?? 'Semua' }},
        Cari {{ $filters['search'] ?? '-' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Bukti</th>
                <th>Tahun</th>
                <th>Kegiatan</th>
                <th>Tanggal</th>
                <th>Penerima</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Lampiran</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lpjs as $lpj)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $lpj->kuitansi->nomor_bukti_lengkap }}</td>
                    <td>{{ $lpj->kuitansi->tahun_anggaran }}</td>
                    <td>{{ $lpj->nama_kegiatan }}</td>
                    <td>{{ $lpj->tanggal_kegiatan->format('d-m-Y') }}</td>
                    <td>{{ $lpj->kuitansi->penerima }}</td>
                    <td>Rp {{ number_format($lpj->kuitansi->jumlah_uang, 0, ',', '.') }}</td>
                    <td>{{ $lpj->completeness_label }}</td>
                    <td>
                        Foto: {{ $lpj->attachmentCount('foto') }}<br>
                        Kwitansi: {{ $lpj->attachmentCount('kwitansi') }}<br>
                        Undangan: {{ $lpj->attachmentCount('undangan') }}
                    </td>
                </tr>
            @empty
                <tr><td colspan="9">Tidak ada data LPJ sesuai filter.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
