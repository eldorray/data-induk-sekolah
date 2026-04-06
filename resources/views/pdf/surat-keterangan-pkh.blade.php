<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Keterangan Kehadiran</title>
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
            margin-bottom: 10px;
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

        .isi-surat {
            text-align: justify;
        }

        .data-section {
            margin: 8px 0 8px 30px;
        }

        .data-section table {
            border-collapse: collapse;
        }

        .data-section td {
            padding: 1px 0;
            vertical-align: top;
            font-size: 11pt;
        }

        .data-section td:first-child {
            width: 180px;
        }

        .data-section td:nth-child(2) {
            width: 10px;
            text-align: center;
        }

        .paragraf {
            margin: 8px 0;
            text-indent: 30px;
        }

        .tabel-kehadiran {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .tabel-kehadiran th,
        .tabel-kehadiran td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
            font-size: 10pt;
        }

        .tabel-kehadiran th {
            font-weight: bold;
            font-size: 9pt;
        }

        .ttd-container {
            margin-top: 15px;
            float: right;
            width: 240px;
            text-align: center;
        }

        .ttd-tempat {
            margin-bottom: 3px;
            font-size: 11pt;
        }

        .ttd-jabatan {
            margin-bottom: 45px;
            font-size: 11pt;
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

        .clear {
            clear: both;
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
        <h2>SURAT KETERANGAN</h2>
        <p>Nomor : {{ $surat->nomor_surat }}</p>
    </div>

    {{-- Isi Surat --}}
    <div class="isi-surat">
        <p>Yang bertanda tangan dibawah ini :</p>

        {{-- Data Kepala Sekolah --}}
        <div class="data-section">
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><strong>{{ $settings['nama_kepala'] ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>NIP</td>
                    <td>:</td>
                    <td>{{ $settings['nip_kepala'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>Kepala Sekolah</td>
                </tr>
                <tr>
                    <td>Unit Kerja</td>
                    <td>:</td>
                    <td>{{ $settings['nama_sekolah'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $settings['alamat'] ?? '' }}, {{ $settings['kelurahan'] ?? '' }}
                        {{ $settings['kecamatan'] ?? '' }}.{{ $settings['kota'] ?? '' }}</td>
                </tr>
            </table>
        </div>

        <p>Menerangkan bahwa :</p>

        {{-- Data Siswa --}}
        <div class="data-section">
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><strong>{{ $surat->siswa->nama_lengkap }}</strong></td>
                </tr>
                <tr>
                    <td>Tempat tanggal lahir</td>
                    <td>:</td>
                    <td>{{ $surat->siswa->tempat_lahir ?? '-' }},
                        {{ $surat->siswa->tanggal_lahir ? $surat->siswa->tanggal_lahir->translatedFormat('d F Y') : '-' }}
                    </td>
                </tr>
                <tr>
                    <td>NIS/NISN</td>
                    <td>:</td>
                    <td>{{ $surat->siswa->nik ?? '-' }} / {{ $surat->siswa->nisn ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>:</td>
                    <td>{{ $surat->siswa->tingkat_rombel ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $surat->siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td>Nama Orang Tua/Wali</td>
                    <td>:</td>
                    <td>{{ $surat->siswa->nama_ayah_kandung ?? ($surat->siswa->nama_wali ?? '-') }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $surat->siswa->alamat ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <p class="paragraf">
            Adalah benar Siswa {{ $settings['nama_sekolah'] ?? '-' }} pada tahun pelajaran
            {{ $surat->tahun_ajaran ?? '-' }} terdaftar sebagai Siswa aktif, dengan prosentase kehadiran
            (Absensi) Semester {{ $surat->semester === 'ganjil' ? 'Ganjil' : 'Genap' }} sebagai
            berikut :
        </p>

        {{-- Tabel Kehadiran --}}
        @php
            $dataAbsensi = $surat->data_absensi ?? [];
            $nomor = 1;
        @endphp

        <table class="tabel-kehadiran">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 30px;">No</th>
                    <th rowspan="2" style="width: 80px;">Bulan</th>
                    <th rowspan="2" style="width: 50px;">Hari<br>Efektif</th>
                    <th colspan="3">Keterangan</th>
                    <th rowspan="2" style="width: 65px;">Jumlah<br>Ketidak<br>Hadiran</th>
                    <th rowspan="2" style="width: 55px;">Jumlah<br>Hadir</th>
                </tr>
                <tr>
                    <th style="width: 45px;">Sakit</th>
                    <th style="width: 45px;">Izin</th>
                    <th style="width: 45px;">Alfa</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($surat->bulan_rekap ?? [] as $bulan)
                    @php
                        $absensi = $dataAbsensi[$bulan] ?? ['hari_efektif' => 0, 'sakit' => 0, 'izin' => 0, 'alfa' => 0];
                        $hariEfektif = (int) ($absensi['hari_efektif'] ?? 0);
                        $sakit = (int) ($absensi['sakit'] ?? 0);
                        $izin = (int) ($absensi['izin'] ?? 0);
                        $alfa = (int) ($absensi['alfa'] ?? 0);
                        $totalTidakHadir = $sakit + $izin + $alfa;
                        $jumlahHadir = $hariEfektif - $totalTidakHadir;
                        if ($jumlahHadir < 0) $jumlahHadir = 0;
                    @endphp
                    <tr>
                        <td>{{ $nomor }}</td>
                        <td style="text-align: left; padding-left: 8px;">{{ $bulan }}</td>
                        <td>{{ $hariEfektif }}</td>
                        <td>{{ $sakit ?: 0 }}</td>
                        <td>{{ $izin ?: 0 }}</td>
                        <td>{{ $alfa ?: 0 }}</td>
                        <td>{{ $totalTidakHadir }}</td>
                        <td>{{ $jumlahHadir }}</td>
                    </tr>
                    @php $nomor++; @endphp
                @endforeach
            </tbody>
        </table>

        <p class="paragraf">
            Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.
        </p>
    </div>

    {{-- Tanda Tangan --}}
    <div class="ttd-container">
        <p class="ttd-tempat">{{ $settings['kecamatan'] ?? '' }},
            {{ $surat->tanggal_surat->translatedFormat('d F Y') }}</p>
        <p class="ttd-jabatan">Kepala Sekolah<br>{{ $settings['nama_sekolah'] ?? '' }}</p>

        <div class="stempel-ttd">
            @if (isset($settings['stempel_path']) && $settings['stempel_path'])
                <img src="{{ public_path('storage/' . $settings['stempel_path']) }}" class="stempel" alt="Stempel">
            @endif
            @if (isset($settings['ttd_kepala_path']) && $settings['ttd_kepala_path'])
                <img src="{{ public_path('storage/' . $settings['ttd_kepala_path']) }}" style="height: 50px;"
                    alt="TTD">
            @endif
        </div>

        <p class="ttd-nama">{{ $settings['nama_kepala'] ?? 'Kepala Sekolah' }}</p>
        <p class="ttd-nip">NIP. {{ $settings['nip_kepala'] ?? '-' }}</p>
    </div>

    <div class="clear"></div>
</body>

</html>
