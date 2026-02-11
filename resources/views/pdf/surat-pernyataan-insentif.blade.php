<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Pernyataan Insentif</title>
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
            margin-bottom: 15px;
        }

        .judul-surat h2 {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
        }

        .isi-surat {
            text-align: justify;
        }

        .data-guru {
            margin: 10px 0 10px 30px;
        }

        .data-guru table {
            border-collapse: collapse;
        }

        .data-guru td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 11pt;
        }

        .data-guru td:first-child {
            width: 130px;
        }

        .data-guru td:nth-child(2) {
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

        .ttd-table {
            width: 100%;
            margin-top: 25px;
        }

        .ttd-table td {
            vertical-align: top;
            text-align: center;
            width: 50%;
            padding: 0 10px;
        }

        .ttd-label {
            margin: 0 0 2px 0;
            font-size: 11pt;
        }

        .ttd-jabatan {
            margin: 0 0 55px 0;
            font-size: 11pt;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            font-size: 11pt;
            margin-top: 2cm;
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
        <h2>SURAT PERNYATAAN</h2>
    </div>

    {{-- Isi Surat --}}
    <div class="isi-surat">
        <p class="paragraf no-indent">
            Yang bertanda tangan di bawah ini :
        </p>

        <div class="data-guru">
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><strong>{{ $guru->full_name_with_title }}</strong></td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ $surat->jabatan }}</td>
                </tr>
                <tr>
                    <td>Unit Kerja</td>
                    <td>:</td>
                    <td>{{ $surat->unit_kerja }}</td>
                </tr>
                <tr>
                    <td>Alamat Unit Kerja</td>
                    <td>:</td>
                    <td>{{ $surat->alamat_unit_kerja ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <p class="paragraf">
            Dengan ini saya menyatakan bahwa saya hanya menerima insentif yang bersumber dari
            {{ $surat->sumber_insentif }} dan tidak menerima di sekolah lain ( tidak double ).
        </p>

        <p class="paragraf">
            Demikian pernyataan ini saya buat dengan benar dan tidak ada paksaan dari pihak manapun.
        </p>
    </div>

    {{-- Tanda Tangan --}}
    <table class="ttd-table">
        <tr>
            <td>
                <p class="ttd-label">Mengetahui,</p>
                <p class="ttd-jabatan">Kepala {{ $settings['nama_sekolah'] ?? '' }}</p>
                <div class="stempel-ttd">
                    @if (!empty($settings['ttd_kepala_path']))
                        <img src="{{ public_path('storage/' . $settings['ttd_kepala_path']) }}" style="height: 50px;"
                            alt="TTD">
                    @endif
                </div>
                <p class="ttd-nama">{{ $settings['nama_kepala'] ?? 'Kepala Sekolah' }}</p>
            </td>
            <td>
                <p class="ttd-label">{{ $settings['kota'] ?? 'Tangerang' }}, &nbsp;&nbsp;&nbsp;
                    {{ $surat->bulan_tahun }}</p>
                <p class="ttd-jabatan">Yang membuat pernyataan</p>
                <div class="stempel-ttd">
                    @if (!empty($settings['stempel_path']))
                        <img src="{{ public_path('storage/' . $settings['stempel_path']) }}" class="stempel"
                            alt="Stempel">
                    @endif
                </div>
                <p class="ttd-nama">{{ $guru->full_name_with_title }}</p>
            </td>
        </tr>
    </table>
</body>

</html>
