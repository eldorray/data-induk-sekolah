<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cover Ijazah (Dummy) - {{ $tahunAjaran->nama_tahun_ajaran }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * { box-sizing: border-box; }

        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 0;
            color: #111;
        }

        .page {
            position: relative;
            width: 210mm;
            height: 297mm;
            padding: 30mm 25mm;
            page-break-after: always;
            overflow: hidden;
        }

        .page:last-child { page-break-after: auto; }

        .border-outer {
            position: absolute;
            top: 12mm;
            left: 12mm;
            right: 12mm;
            bottom: 12mm;
            border: 3px double #1f2937;
            padding: 10mm;
        }

        .border-inner {
            position: absolute;
            top: 18mm;
            left: 18mm;
            right: 18mm;
            bottom: 18mm;
            border: 1px solid #1f2937;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 110pt;
            font-weight: 900;
            color: rgba(239, 68, 68, 0.12);
            letter-spacing: 20px;
            z-index: 0;
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
            text-align: center;
            padding-top: 10mm;
        }

        .dummy-badge {
            display: inline-block;
            border: 2px solid #dc2626;
            color: #dc2626;
            padding: 5px 14px;
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 20mm;
        }

        .title {
            font-size: 32pt;
            font-weight: bold;
            letter-spacing: 16px;
            margin: 0 0 6mm 0;
        }

        .subtitle {
            font-size: 14pt;
            letter-spacing: 4px;
            margin-bottom: 12mm;
        }

        .school-name {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 3mm;
        }

        .school-address {
            font-size: 10pt;
            color: #374151;
            margin-bottom: 20mm;
        }

        .identity {
            margin: 15mm auto 0 auto;
            width: 130mm;
            text-align: left;
            font-size: 12pt;
            line-height: 1.9;
        }

        .identity table {
            width: 100%;
            border-collapse: collapse;
        }

        .identity td {
            padding: 2mm 0;
            vertical-align: top;
        }

        .identity td.label { width: 55mm; font-weight: normal; }
        .identity td.colon { width: 5mm; }
        .identity td.value { font-weight: bold; border-bottom: 1px dotted #6b7280; }

        .tahun-ajaran {
            margin-top: 25mm;
            font-size: 14pt;
            font-weight: bold;
        }

        .footer-dummy {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 15mm;
            text-align: center;
            font-size: 9pt;
            color: #991b1b;
            font-style: italic;
        }
    </style>
</head>
<body>
    @php
        $namaSekolah = $settings['nama_sekolah'] ?? ($settings['nama_madrasah'] ?? 'Nama Madrasah');
        $alamatSekolah = $settings['alamat_sekolah'] ?? ($settings['alamat_madrasah'] ?? '');
    @endphp

    @foreach ($siswas as $siswa)
        <div class="page">
            <div class="border-outer"></div>
            <div class="border-inner"></div>
            <div class="watermark">DUMMY</div>

            <div class="content">
                <div class="dummy-badge">DOKUMEN DUMMY &middot; BUKAN IJAZAH RESMI</div>

                <div class="title">IJAZAH</div>
                <div class="subtitle">MADRASAH IBTIDAIYAH</div>

                <div class="school-name">{{ strtoupper($namaSekolah) }}</div>
                @if ($alamatSekolah)
                    <div class="school-address">{{ $alamatSekolah }}</div>
                @endif

                <div class="identity">
                    <table>
                        <tr>
                            <td class="label">Nama</td>
                            <td class="colon">:</td>
                            <td class="value">{{ strtoupper($siswa->nama_lengkap) }}</td>
                        </tr>
                        <tr>
                            <td class="label">Tempat, Tanggal Lahir</td>
                            <td class="colon">:</td>
                            <td class="value">
                                {{ $siswa->tempat_lahir ?: '-' }}@if ($siswa->tanggal_lahir), {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->locale('id')->translatedFormat('d F Y') }}@endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label">NISN</td>
                            <td class="colon">:</td>
                            <td class="value">{{ $siswa->nisn ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Nama Orang Tua/Wali</td>
                            <td class="colon">:</td>
                            <td class="value">{{ $siswa->nama_ayah_kandung ?: ($siswa->nama_wali ?: '-') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Nomor Peserta</td>
                            <td class="colon">:</td>
                            <td class="value">{{ $siswa->nik ?: '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="tahun-ajaran">TAHUN AJARAN {{ $tahunAjaran->nama_tahun_ajaran }}</div>
            </div>

            <div class="footer-dummy">* Dokumen ini hanyalah simulasi/contoh dan tidak memiliki kekuatan hukum sebagai ijazah resmi.</div>
        </div>
    @endforeach
</body>
</html>