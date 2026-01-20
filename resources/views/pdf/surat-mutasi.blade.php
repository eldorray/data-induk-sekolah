<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Keterangan Pindah/Keluar</title>
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

        .data-siswa {
            margin: 8px 0 8px 30px;
        }

        .data-siswa table {
            border-collapse: collapse;
        }

        .data-siswa td {
            padding: 1px 0;
            vertical-align: top;
            font-size: 11pt;
        }

        .data-siswa td:first-child {
            width: 140px;
        }

        .data-siswa td:nth-child(2) {
            width: 10px;
            text-align: center;
        }

        .paragraf {
            margin: 8px 0;
            text-indent: 30px;
        }

        .ttd-container {
            margin-top: 15px;
            float: right;
            width: 220px;
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
        @if ($settings['kop_surat_path'])
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
        <h2>SURAT KETERANGAN PINDAH/KELUAR</h2>
        <p>Nomor : {{ $mutasi->nomor_surat }}</p>
    </div>

    {{-- Isi Surat --}}
    <div class="isi-surat">
        <p>Surat ini menerangkan bahwa siswa,</p>

        <div class="data-siswa">
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><strong>{{ $mutasi->siswa->nama_lengkap }}</strong></td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $mutasi->siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td>Tempat, tanggal lahir</td>
                    <td>:</td>
                    <td>{{ $mutasi->siswa->tempat_lahir ?? '-' }},
                        {{ $mutasi->siswa->tanggal_lahir ? $mutasi->siswa->tanggal_lahir->format('d-m-Y') : '-' }}</td>
                </tr>
                <tr>
                    <td>NISN</td>
                    <td>:</td>
                    <td>{{ $mutasi->siswa->nisn ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tingkat/Kelas</td>
                    <td>:</td>
                    <td>{{ $mutasi->siswa->tingkat_rombel ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <p>pada tanggal {{ $mutasi->tanggal_mutasi->translatedFormat('d-m-Y') }} telah tidak menjalankan Kegiatan
            Belajar Mengajar pada,</p>

        <div class="data-siswa">
            <table>
                <tr>
                    <td>Nama Satuan Pendidikan</td>
                    <td>:</td>
                    <td><strong>{{ $settings['nama_sekolah'] ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>NPSN</td>
                    <td>:</td>
                    <td>{{ $settings['npsn'] ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $settings['alamat'] ?? '' }}, {{ $settings['kelurahan'] ?? '' }}
                        {{ $settings['kecamatan'] ?? '' }} {{ $settings['kota'] ?? '' }}
                        {{ $settings['provinsi'] ?? '' }} {{ $settings['kode_pos'] ?? '' }}</td>
                </tr>
            </table>
        </div>

        <p class="paragraf">
            Dikarenakan pindah satuan pendidikan/mutasi, {{ $mutasi->alasan_mutasi }}.
            Hal-hal yang berkaitan dengan data pendidikan siswa pada sistem informasi pendataan pendidikan
            Kementerian Agama (EMIS) telah dijalankan sebagaimana mestinya.
        </p>

        <p class="paragraf">
            Demikian surat keterangan ini dibuat, untuk diketahui dan dipergunakan sebagaimana mestinya.
        </p>
    </div>

    {{-- Tanda Tangan --}}
    <div class="ttd-container">
        <p class="ttd-tempat">{{ $settings['kecamatan'] ?? '' }},
            {{ $mutasi->tanggal_surat->translatedFormat('d F Y') }}</p>
        <p class="ttd-jabatan">Yang menerangkan, Kepala<br>{{ $settings['nama_sekolah'] ?? '' }}</p>

        <div class="stempel-ttd">
            @if ($settings['stempel_path'])
                <img src="{{ public_path('storage/' . $settings['stempel_path']) }}" class="stempel" alt="Stempel">
            @endif
            @if ($settings['ttd_kepala_path'])
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
