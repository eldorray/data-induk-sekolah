<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SK Guru Tetap Yayasan</title>
    <style>
        @page {
            size: 215.9mm 330.2mm;
            margin: 1cm 1.5cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
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
            margin-bottom: 3px;
        }

        .judul-surat h2 {
            font-size: 11pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .judul-surat p {
            font-size: 10pt;
            margin: 2px 0 0 0;
        }

        .judul-surat .tentang {
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 8px;
        }

        .section {
            margin: 10px 0;
        }

        .section-title {
            width: 100px;
            display: inline-block;
            vertical-align: top;
        }

        .section-content {
            display: inline-block;
            vertical-align: top;
        }

        .section-table {
            width: 100%;
            border-collapse: collapse;
        }

        .section-table td {
            padding: 1px 0;
            vertical-align: top;
            font-size: 10pt;
        }

        .section-table td:first-child {
            width: 85px;
        }

        .section-table td:nth-child(2) {
            width: 10px;
            text-align: center;
        }

        .section-table .number {
            width: 20px;
            text-align: right;
            padding-right: 5px;
        }

        .memutuskan {
            text-align: center;
            font-weight: bold;
            margin: 10px 0;
            font-size: 11pt;
        }

        .keputusan-table {
            width: 100%;
            border-collapse: collapse;
        }

        .keputusan-table td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 10pt;
        }

        .keputusan-table td:first-child {
            width: 65px;
        }

        .keputusan-table td:nth-child(2) {
            width: 10px;
            text-align: center;
        }

        .data-guru {
            margin-left: 90px;
        }

        .data-guru table {
            border-collapse: collapse;
        }

        .data-guru td {
            padding: 0;
            vertical-align: top;
            font-size: 10pt;
        }

        .data-guru td:first-child {
            width: 110px;
        }

        .data-guru td:nth-child(2) {
            width: 10px;
            text-align: center;
        }

        .ttd-container {
            margin-top: 10px;
            float: right;
            width: 200px;
            text-align: center;
        }

        .ttd-tempat {
            margin-bottom: 2px;
            font-size: 10pt;
        }

        .ttd-jabatan {
            margin-bottom: 50px;
            font-size: 10pt;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            font-size: 10pt;
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

        ol {
            margin: 0;
            padding-left: 18px;
        }

        ol li {
            padding-left: 3px;
            margin-bottom: 1px;
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
            <p style="font-size: 10pt; margin: 0;">
                Akte Notaris : IMRON, S.H. No. 31 Tgl. 23 - 07 - 2016
            </p>
            <p style="font-size: 9pt; margin: 0;">
                {{ $settings['alamat'] ?? '' }}, {{ $settings['kelurahan'] ?? '' }},
                {{ $settings['kecamatan'] ?? '' }} {{ $settings['kota'] ?? '' }} Telp.{{ $settings['telepon'] ?? '' }}
            </p>
        @endif
    </div>

    <div class="garis-kop"></div>

    {{-- Judul Surat --}}
    <div class="judul-surat">
        <h2>KEPUTUSAN KETUA YAYASAN DAARUL HIKMAH AL MADANI</h2>
        <h2>PENGANGKATAN GURU TETAP YAYASAN (GTY)</h2>
        <p>NOMOR : {{ $sk->nomor_sk }}</p>

        <p class="tentang">TENTANG</p>
        <p><strong>PENGANGKATAN PENDIDIK DAN TENAGA KEPENDIDIKAN</strong></p>
        <p><strong>MI DAARUL HIKMAH</strong></p>
    </div>

    {{-- Menimbang --}}
    <table class="section-table">
        <tr>
            <td>Menimbang</td>
            <td>:</td>
            <td>
                <ol>
                    <li>Bahwa untuk kelancaran proses kegiatan belajar mengajar dan peningkatan mutu pendidikan di MI
                        Daarul Hikmah dipandang perlu mengangkat Pendidik dan Tenaga Kependidikan.</li>
                    <li>Bahwa yang namanya tercantum pada surat keputusan ini dipandang cakap dan mampu melaksanakan
                        kegiatan belajar mengajar dan tertib administrasi di MI Daarul Hikmah</li>
                </ol>
            </td>
        </tr>
    </table>

    {{-- Mengingat --}}
    <table class="section-table">
        <tr>
            <td>Mengingat</td>
            <td>:</td>
            <td>
                <ol>
                    <li>Undang-Undang No. 20 Tahun 2003 tentang Sistem Pendidikan Nasional;</li>
                    <li>Undang-Undang No. 14 Tahun 2005 tentang Guru dan Dosen;</li>
                    <li>Peraturan Pemerintah No. 39 Tahun 2005 tentang Standar Nasional Pendidikan;</li>
                    <li>Keputusan Menteri Agama RI No. 368 Tahun 1993 Tentang MI, Keputusan Menteri Agama RI No. 369
                        Tahun 1993 Tentang MTs Keputusan Menteri Agama RI No. 370 Tahun 1993 Tentang MA.</li>
                    <li>Peraturan Menteri Agama No. 11 Tahun 2011 tentang Pedoman Pembentukan dan Penyempurnaan
                        Organisasi Instansi Vertikal dan Unit Pelaksana Teknis Kementerian Agama;</li>
                    <li>Peraturan Menteri Agama No. 80 Tahun 2013 tentang Organisasi dan Tata Kerja Kementerian Agama;
                    </li>
                    <li>Peraturan Menteri Agama No. 90 Tahun 2013 tentang Penyelenggaraan Pendidikan Madrasah;</li>
                </ol>
            </td>
        </tr>
    </table>

    {{-- Memperhatikan --}}
    <table class="section-table">
        <tr>
            <td>Memperhatikan</td>
            <td>:</td>
            <td>
                <ol>
                    <li>Anggaran Dasar dan Anggaran Rumah Tangga Yayasan Daarul Hikmah Al Madani</li>
                    <li>Keputusan musyawarah pengurus yayasan tanggal 02 Juli 2021;</li>
                </ol>
            </td>
        </tr>
    </table>

    {{-- Memutuskan --}}
    <div class="memutuskan">MEMUTUSKAN</div>

    {{-- Menetapkan --}}
    <table class="keputusan-table">
        <tr>
            <td>MENETAPKAN</td>
            <td>:</td>
            <td></td>
        </tr>
        <tr>
            <td>Pertama</td>
            <td>:</td>
            <td>
                Mengangkat Pendidik / Tenaga Kependidikan kepada :
                <div class="data-guru">
                    <table>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td><strong>{{ $sk->guru->full_name_with_title }}</strong></td>
                        </tr>
                        <tr>
                            <td>Tempat, Tanggal lahir</td>
                            <td>:</td>
                            <td>{{ strtoupper($sk->tempat_lahir ?? '-') }},
                                {{ $sk->tanggal_lahir ? $sk->tanggal_lahir->translatedFormat('d F Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td>NUPTK</td>
                            <td>:</td>
                            <td>{{ $sk->nuptk ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Pendidikan Terakhir</td>
                            <td>:</td>
                            <td>{{ $sk->pendidikan_terakhir ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td>{{ strtoupper($sk->jabatan ?? 'GURU') }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td>Kedua</td>
            <td>:</td>
            <td>Surat Keputusan ini berlaku mulai {{ $sk->berlaku_mulai->translatedFormat('d F Y') }} sampai dengan
                {{ $sk->berlaku_sampai->translatedFormat('d F Y') }}.</td>
        </tr>
        <tr>
            <td>Ketiga</td>
            <td>:</td>
            <td>Kepadanya diberi beban kewajiban sesuai ketentuan yang berlaku di madrasah.</td>
        </tr>
        <tr>
            <td>Keempat</td>
            <td>:</td>
            <td>Apabila dalam surat keputusan ini terdapat kekeliruan dikemudian hari, maka akan ditinjau kembali dan
                diperbaiki sebagaimana mestinya.</td>
        </tr>
        <tr>
            <td>Kelima</td>
            <td>:</td>
            <td>Asli Surat Keputusan ini diberikan kepada yang bersangkutan untuk dilaksanakan sebagaimana mestinya.
            </td>
        </tr>
    </table>

    {{-- Tanda Tangan --}}
    <div class="ttd-container">
        <p class="ttd-tempat">Ditetapkan di : {{ $sk->tempat_penetapan }}</p>
        <p class="ttd-tempat">Pada tanggal : {{ $sk->tanggal_penetapan->translatedFormat('d F Y') }}</p>
        <p class="ttd-jabatan">{{ $sk->penandatangan_jabatan }},</p>

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
    </div>

    <div class="clear"></div>
</body>

</html>
