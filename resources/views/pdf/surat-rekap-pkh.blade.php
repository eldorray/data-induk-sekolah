<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Rekap Absensi Siswa</title>
    <style>
        @page {
            margin: 1.5cm 2cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.3;
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
            margin: 5px 0 10px 0;
            padding: 1px 0;
        }

        .judul-surat {
            text-align: center;
            margin-bottom: 15px;
        }

        .judul-surat h2 {
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
        }

        .judul-surat p {
            font-size: 11pt;
            margin: 3px 0 0 0;
        }

        .data-siswa {
            margin: 10px 0 15px 0;
        }

        .data-siswa table {
            border-collapse: collapse;
        }

        .data-siswa td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 11pt;
        }

        .data-siswa td:first-child {
            width: 160px;
        }

        .data-siswa td:nth-child(2) {
            width: 10px;
            text-align: center;
        }

        .section-bulan {
            margin: 10px 0;
        }

        .section-bulan p {
            font-size: 11pt;
            margin: 5px 0;
        }

        .tabel-absensi {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0 10px 0;
        }

        .tabel-absensi th,
        .tabel-absensi td {
            border: 1px solid #000;
            padding: 5px 10px;
            text-align: center;
            font-size: 11pt;
        }

        .tabel-absensi th {
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .paragraf {
            margin: 10px 0;
            text-indent: 0;
            font-size: 11pt;
        }

        .ttd-wrapper {
            margin-top: 20px;
            width: 100%;
        }

        .ttd-wrapper table {
            width: 100%;
        }

        .ttd-wrapper td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 11pt;
        }

        .ttd-tempat {
            margin-bottom: 3px;
            font-size: 11pt;
        }

        .ttd-jabatan {
            font-size: 11pt;
        }

        .ttd-space {
            height: 60px;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            font-size: 11pt;
        }

        .ttd-nip {
            font-size: 10pt;
        }

        .stempel-ttd {
            position: relative;
        }

        .stempel {
            position: absolute;
            left: -25px;
            top: 5px;
            width: 80px;
            opacity: 0.8;
        }
    </style>
</head>

<body>
    {{-- KOP Surat --}}
    <div class="kop-surat">
        @if (isset($settings['kop_surat_path']) && $settings['kop_surat_path'])
            <img src="{{ public_path('storage/' . $settings['kop_surat_path']) }}" alt="Kop Surat">
        @else
            <h2 style="font-size: 16pt; margin: 0;">{{ $settings['nama_yayasan'] ?? 'YAYASAN PENDIDIKAN' }}</h2>
            <h1 style="font-size: 18pt; margin: 5px 0;">{{ $settings['nama_sekolah'] ?? 'NAMA SEKOLAH' }}</h1>
            <p style="font-size: 10pt; margin: 0;">
                {{ $settings['alamat'] ?? '' }}, {{ $settings['kelurahan'] ?? '' }},
                {{ $settings['kecamatan'] ?? '' }}<br>
                {{ $settings['kota'] ?? '' }} {{ $settings['kode_pos'] ?? '' }} Telp.
                {{ $settings['telepon'] ?? '' }}
            </p>
            <p style="font-size: 10pt; margin: 0;">
                <strong>NSM:</strong> {{ $settings['nsm'] ?? '' }} &nbsp;&nbsp;&nbsp; <strong>NPSN:</strong>
                {{ $settings['npsn'] ?? '' }}
            </p>
        @endif
    </div>

    <div class="garis-kop"></div>

    {{-- Judul Surat --}}
    <div class="judul-surat">
        <h2>REKAP ABSENSI SISWA {{ strtoupper($settings['nama_sekolah'] ?? '') }} TAHUN AJARAN
            {{ $surat->tahun_ajaran ?? '-' }}</h2>
    </div>

    {{-- Data Identitas Siswa --}}
    <div class="data-siswa">
        <table>
            <tr>
                <td>NAMA PESERTA DIDIK</td>
                <td>:</td>
                <td><strong>{{ $surat->siswa->nama_lengkap }}</strong></td>
            </tr>
            <tr>
                <td>NISN</td>
                <td>:</td>
                <td>{{ $surat->siswa->nisn ?? '-' }}</td>
            </tr>
            <tr>
                <td>KELAS</td>
                <td>:</td>
                <td>{{ $surat->siswa->tingkat_rombel ?? '-' }}</td>
            </tr>
            <tr>
                <td>SEKOLAH</td>
                <td>:</td>
                <td>{{ $settings['nama_sekolah'] ?? '-' }}</td>
            </tr>
            <tr>
                <td>BULAN</td>
                <td>:</td>
                <td>{{ implode(', ', $surat->bulan_rekap ?? []) }}</td>
            </tr>
        </table>
    </div>

    {{-- Rekap Absensi Per Bulan --}}
    <p>Berikut rekap absensi :</p>

    @php
        $dataAbsensi = $surat->data_absensi ?? [];
        $totalSakit = 0;
        $totalIzin = 0;
        $totalAlfa = 0;
        $nomor = 1;
    @endphp

    @foreach ($surat->bulan_rekap ?? [] as $bulan)
        @php
            $absensi = $dataAbsensi[$bulan] ?? ['sakit' => 0, 'izin' => 0, 'alfa' => 0];
            $sakit = (int) ($absensi['sakit'] ?? 0);
            $izin = (int) ($absensi['izin'] ?? 0);
            $alfa = (int) ($absensi['alfa'] ?? 0);
            $totalSakit += $sakit;
            $totalIzin += $izin;
            $totalAlfa += $alfa;
        @endphp

        <div class="section-bulan">
            <p>{{ $nomor }}. Bulan {{ $bulan }} {{ explode('/', $surat->tahun_ajaran ?? '')[1] ?? date('Y') }}
            </p>
            <table class="tabel-absensi">
                <thead>
                    <tr>
                        <th>SAKIT</th>
                        <th>IZIN</th>
                        <th>ALFA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $sakit ?: '-' }}</td>
                        <td>{{ $izin ?: '-' }}</td>
                        <td>{{ $alfa ?: '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @php
            $nomor++;
        @endphp
    @endforeach

    {{-- Total Absensi --}}
    <div class="section-bulan">
        <p>{{ $nomor }}. JUMLAH ABSEN BULAN {{ strtoupper(implode(', ', $surat->bulan_rekap ?? [])) }}</p>
        <table class="tabel-absensi">
            <thead>
                <tr>
                    <th>SAKIT</th>
                    <th>IZIN</th>
                    <th>ALFA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $totalSakit ?: '-' }}</td>
                    <td>{{ $totalIzin ?: '-' }}</td>
                    <td>{{ $totalAlfa ?: '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Penutup --}}
    <p class="paragraf">Demikian rekap absensi ini kami buat, semoga dapat digunakan dengan semestinya.</p>

    {{-- Tanda Tangan (2 kolom) --}}
    <div class="ttd-wrapper">
        <table>
            <tr>
                <td>
                    <p class="ttd-tempat">Mengetahui</p>
                    <p class="ttd-jabatan">Kepala {{ $settings['nama_sekolah'] ?? '' }}</p>
                    <div class="ttd-space">
                        <div class="stempel-ttd">
                            @if (isset($settings['stempel_path']) && $settings['stempel_path'])
                                <img src="{{ public_path('storage/' . $settings['stempel_path']) }}" class="stempel"
                                    alt="Stempel">
                            @endif
                            @if (isset($settings['ttd_kepala_path']) && $settings['ttd_kepala_path'])
                                <img src="{{ public_path('storage/' . $settings['ttd_kepala_path']) }}"
                                    style="height: 50px;" alt="TTD">
                            @endif
                        </div>
                    </div>
                    <p class="ttd-nama">{{ $settings['nama_kepala'] ?? 'Kepala Sekolah' }}</p>
                    <p class="ttd-nip">NIP. {{ $settings['nip_kepala'] ?? '-' }}</p>
                </td>
                <td>
                    <p class="ttd-tempat">{{ $settings['kecamatan'] ?? '' }},
                        {{ $surat->tanggal_surat->translatedFormat('d F Y') }}</p>
                    <p class="ttd-jabatan">Wali Kelas</p>
                    <div class="ttd-space"></div>
                    <p class="ttd-nama">{{ $surat->nama_wali_kelas ?? '-' }}</p>
                    <p class="ttd-nip">NIP. {{ $surat->nip_wali_kelas ?? '-' }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
