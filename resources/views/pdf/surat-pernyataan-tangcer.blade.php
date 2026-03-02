<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Keterangan Kepala Madrasah - TANGCER</title>
    <style>
        @page {
            margin: 1.5cm 2cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
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
            margin: 5px 0 15px 0;
            padding: 1px 0;
        }

        .judul-surat {
            text-align: center;
            margin-bottom: 5px;
        }

        .judul-surat h2 {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
        }

        .nomor-surat {
            text-align: center;
            margin-bottom: 15px;
        }

        .nomor-surat p {
            margin: 0;
            font-size: 11pt;
        }

        .isi-surat {
            text-align: justify;
        }

        .data-section {
            margin: 10px 0 10px 30px;
        }

        .data-section table {
            border-collapse: collapse;
        }

        .data-section td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 11pt;
        }

        .data-section td:first-child {
            width: 130px;
        }

        .data-section td:nth-child(2) {
            width: 10px;
            text-align: center;
        }

        .paragraf {
            margin: 10px 0;
            text-indent: 30px;
            text-align: justify;
        }

        .no-indent {
            text-indent: 0;
        }

        .ttd-section {
            margin-top: 25px;
            text-align: right;
            padding-right: 20px;
        }

        .ttd-label {
            margin: 0 0 2px 0;
            font-size: 11pt;
        }

        .ttd-jabatan {
            margin: 0 0 5px 0;
            font-size: 11pt;
            text-decoration: underline;
        }

        .ttd-nama {
            font-weight: bold;
            font-size: 11pt;
            margin-top: 60px;
        }

        .ttd-nama-underline {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    {{-- KOP Surat --}}
    <div class="kop-surat">
        @if (!empty($settings['kop_surat_path']))
            <img src="{{ public_path('storage/' . $settings['kop_surat_path']) }}" alt="Kop Surat">
        @else
            <h2 style="font-size: 16pt; margin: 0;">{{ $settings['nama_yayasan'] ?? 'YAYASAN PENDIDIKAN' }}</h2>
            <h1 style="font-size: 18pt; margin: 5px 0;">{{ $settings['nama_sekolah'] ?? 'NAMA SEKOLAH' }}</h1>
            <p style="font-size: 10pt; margin: 0;">
                {{ $settings['alamat'] ?? '' }}, {{ $settings['kelurahan'] ?? '' }},
                {{ $settings['kecamatan'] ?? '' }}<br>
                {{ $settings['kota'] ?? '' }} {{ $settings['kode_pos'] ?? '' }} Telp. {{ $settings['telepon'] ?? '' }}
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
        <h2>SURAT KETERANGAN KEPALA MADRASAH</h2>
    </div>
    <div class="nomor-surat">
        <p><u>Nomor :</u> {{ $surat->nomor_surat }}</p>
    </div>

    {{-- Isi Surat --}}
    <div class="isi-surat">
        <p class="paragraf no-indent">
            Yang bertanda tangan dibawah ini :
        </p>

        {{-- Data Kepala Sekolah --}}
        <div class="data-section">
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $settings['nama_kepala'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>Kepala Madrasah</td>
                </tr>
                <tr>
                    <td>Nama Madrasah</td>
                    <td>:</td>
                    <td>{{ $settings['nama_sekolah'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $settings['alamat'] ?? '' }}, {{ $settings['kelurahan'] ?? '' }}
                        {{ $settings['kecamatan'] ?? '' }}</td>
                </tr>
            </table>
        </div>

        <p class="paragraf no-indent">
            Atas Nama :
        </p>

        {{-- Data Siswa --}}
        <div class="data-section">
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $siswa->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td>Kelas</td>
                    <td>:</td>
                    <td>{{ $siswa->tingkat_rombel ?? '-' }}</td>
                </tr>
                <tr>
                    <td>NISN</td>
                    <td>:</td>
                    <td><strong>{{ $siswa->nisn ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>Nama Wali</td>
                    <td>:</td>
                    <td>{{ $siswa->nama_wali ?? '-' }}</td>
                </tr>
            </table>
        </div>

        {{-- Paragraf Isi (editable) --}}
        <p class="paragraf">
            {{ $surat->isi_surat }}
        </p>

        {{-- Paragraf Tujuan (editable) --}}
        <p class="paragraf">
            {{ $surat->isi_tujuan }}
        </p>
    </div>

    {{-- Tanda Tangan --}}
    <div class="ttd-section">
        <p class="ttd-label">{{ $settings['kota'] ?? 'Tangerang' }},
            {{ $surat->tanggal_surat?->translatedFormat('d F Y') ?? '-' }}</p>
        <p class="ttd-jabatan">Kepala Madrasah</p>

        <div style="position: relative; display: inline-block;">
            @if (!empty($settings['ttd_kepala_path']))
                <img src="{{ public_path('storage/' . $settings['ttd_kepala_path']) }}" style="height: 50px;"
                    alt="TTD">
            @endif
        </div>

        <p class="ttd-nama">
            (<span class="ttd-nama-underline">{{ $settings['nama_kepala'] ?? 'Kepala Madrasah' }}</span>)
        </p>
    </div>
</body>

</html>
