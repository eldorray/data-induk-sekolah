<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SK Pembagian Tugas Mengajar</title>
    <style>
        @page {
            size: 215.9mm 330.2mm;
            margin: 1cm 1.5cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 9pt;
            line-height: 1.2;
            color: #000;
        }

        .kop-surat {
            text-align: center;
            margin-bottom: 5px;
        }

        .kop-surat img {
            width: 100%;
            height: auto;
        }

        .garis-kop {
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            margin: 3px 0 8px 0;
            padding: 1px 0;
        }

        .judul-surat {
            text-align: center;
            margin-bottom: 10px;
        }

        .judul-surat h2 {
            font-size: 10pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .judul-surat p {
            font-size: 9pt;
            margin: 2px 0 0 0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 3px 5px;
            text-align: center;
            font-size: 9pt;
        }

        .data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .data-table td.left {
            text-align: left;
        }

        .data-table td.center {
            text-align: center;
        }

        .ttd-container {
            margin-top: 15px;
            float: right;
            width: 200px;
            text-align: center;
        }

        .ttd-tempat {
            margin-bottom: 2px;
            font-size: 9pt;
        }

        .ttd-jabatan {
            margin-bottom: 50px;
            font-size: 9pt;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            font-size: 9pt;
        }

        .ttd-nip {
            font-size: 8pt;
        }

        .stempel-ttd {
            position: relative;
        }

        .stempel {
            position: absolute;
            left: -30px;
            top: 5px;
            width: 80px;
            opacity: 0.8;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>
    {{-- KOP Surat --}}
    <div class="kop-surat">
        @if ($settings['kop_surat_path'])
            <img src="{{ public_path('storage/' . $settings['kop_surat_path']) }}" alt="Kop Surat">
        @else
            <h2 style="font-size: 14pt; margin: 0;">
                {{ $settings['nama_yayasan'] ?? 'YAYASAN PENDIDIKAN DAARUL HIKMAH AL MADANI' }}</h2>
            <h1 style="font-size: 16pt; margin: 5px 0;">{{ $settings['nama_sekolah'] ?? 'MI DAARUL HIKMAH' }}</h1>
            <p style="font-size: 10pt; margin: 0;">
                Akte Notaris : IMRON, S.H. No. 31 Tgl. 23 - 07 - 2016
            </p>
            <p style="font-size: 9pt; margin: 0;">
                {{ $settings['alamat'] ?? '' }}, {{ $settings['kelurahan'] ?? '' }},
                {{ $settings['kecamatan'] ?? '' }} {{ $settings['kota'] ?? '' }} Telp.{{ $settings['telepon'] ?? '' }}
            </p>
            <p style="font-size: 9pt; margin: 0;">
                <strong>NSM :</strong> {{ $settings['nsm'] ?? '' }} &nbsp;&nbsp;&nbsp; <strong>NPSN :</strong>
                {{ $settings['npsn'] ?? '' }}
            </p>
        @endif
    </div>

    <div class="garis-kop"></div>

    {{-- Judul Surat --}}
    <div class="judul-surat">
        <h2>PEMBAGIAN TUGAS MENGAJAR GURU DALAM</h2>
        <h2>PROSES BELAJAR MENGAJAR</h2>
        <h2>SEMESTER {{ $sk->semester == '1' ? 'I' : 'II' }} TAHUN AJARAN {{ $sk->tahun_pelajaran }}</h2>
    </div>

    {{-- Tabel Pembagian Tugas --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 180px;">Nama</th>
                <th style="width: 100px;">Jabatan</th>
                <th style="width: 100px;">Jenis Guru</th>
                <th style="width: 100px;">Tugas Mengajar</th>
                <th style="width: 70px;">Jumlah Jam</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sk->details as $index => $detail)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td class="left">{{ $detail->guru?->full_name_with_title }}</td>
                    <td class="center">{{ $detail->jabatan }}</td>
                    <td class="center">{{ $detail->jenis_guru }}</td>
                    <td class="center">{{ $detail->tugas_mengajar }}</td>
                    <td class="center"><strong>{{ $detail->jumlah_jam ?? '-' }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <div class="ttd-container">
        <p class="ttd-tempat">{{ $sk->tempat_penetapan }}, {{ $sk->tanggal_penetapan->translatedFormat('d F Y') }}</p>
        <p class="ttd-jabatan">{{ $sk->penandatangan_jabatan }}</p>

        <div class="stempel-ttd">
            @if ($settings['stempel_path'])
                <img src="{{ public_path('storage/' . $settings['stempel_path']) }}" class="stempel" alt="Stempel">
            @endif
            @if ($settings['ttd_kepala_path'])
                <img src="{{ public_path('storage/' . $settings['ttd_kepala_path']) }}" style="height: 50px;"
                    alt="TTD">
            @endif
        </div>

        <p class="ttd-nama">{{ $sk->penandatangan_nama }}</p>
        @if ($sk->penandatangan_nip)
            <p class="ttd-nip">NIP. {{ $sk->penandatangan_nip }}</p>
        @endif
    </div>

    <div class="clear"></div>
</body>

</html>
