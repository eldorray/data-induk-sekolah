<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Cetak Dokumen Menerima Siswa Pindahan</title>
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

        .page-break {
            page-break-after: always;
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

        .data-siswa td.label {
            width: 160px;
            max-width: 160px;
            min-width: 160px;
            white-space: nowrap;
        }

        .data-siswa td.sep {
            width: 15px;
            text-align: center;
        }

        .paragraf {
            margin: 8px 0;
            text-indent: 30px;
        }

        .syarat-list {
            margin: 14px 0 14px 0;
        }

        .syarat-list table.indent-table {
            border-collapse: collapse;
            width: 100%;
        }

        .syarat-list table.indent-table td.indent-cell {
            width: 70px;
        }

        .syarat-list table.list-table {
            border-collapse: collapse;
            width: 100%;
        }

        .syarat-list td.syarat-no {
            width: 30px;
            padding-right: 6px;
        }

        .syarat-list td.syarat-isi {
            text-align: justify;
            font-size: 11pt;
        }

        .ttd-container {
            margin-top: 20px;
            float: right;
            width: 240px;
            text-align: center;
        }

        .ttd-tempat {
            margin-bottom: 3px;
            font-size: 11pt;
        }

        .ttd-jabatan {
            margin-bottom: 50px;
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
    @foreach ($surats as $surat)
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
            <h2>SURAT KETERANGAN MENERIMA SISWA PINDAHAN</h2>
            <p>Nomor : {{ $surat->nomor_surat }}</p>
        </div>

        {{-- Isi Surat --}}
        <div class="isi-surat">
            <p class="paragraf">Yang bertanda tangan di bawah ini:</p>

            <div class="data-siswa">
                <table>
                    <tr><td class="label">Nama</td><td class="sep">:</td>
                        <td>{{ $settings['nama_kepala'] ?? '-' }}</td>
                    </tr>
                    <tr><td class="label">NIP</td><td class="sep">:</td>
                        <td>{{ $settings['nip_kepala'] ?? '-' }}</td>
                    </tr>
                    <tr><td class="label">Jabatan</td><td class="sep">:</td>
                        <td>Kepala Sekolah</td>
                    </tr>
                    <tr><td class="label">Unit Kerja</td><td class="sep">:</td>
                        <td>{{ $settings['nama_sekolah'] ?? '-' }} Kec. {{ $settings['kecamatan'] ?? '-' }}
                            {{ $settings['kota'] ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <p class="paragraf">Menerangkan bahwa:</p>

            <div class="data-siswa">
                <table>
                    <tr><td class="label">Nama</td><td class="sep">:</td>
                        <td><strong>{{ $surat->nama_siswa }}</strong></td>
                    </tr>
                    <tr><td class="label">Tempat, Tgl Lahir</td><td class="sep">:</td>
                        <td>
                            {{ $surat->tempat_lahir ?? '-' }},
                            {{ $surat->tanggal_lahir ? $surat->tanggal_lahir->translatedFormat('d F Y') : '-' }}
                        </td>
                    </tr>
                    <tr><td class="label">Kelas</td><td class="sep">:</td>
                        <td>{{ $surat->kelas ?? '-' }}</td>
                    </tr>
                    <tr><td class="label">Jenis Kelamin</td><td class="sep">:</td>
                        <td>{{ $surat->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr><td class="label">Asal Sekolah</td><td class="sep">:</td>
                        <td>{{ $surat->asal_sekolah ?? '-' }}</td>
                    </tr>
                    <tr><td class="label">Nama Orang Tua</td><td class="sep">:</td>
                        <td>{{ $surat->nama_orang_tua ?? '-' }}</td>
                    </tr>
                    <tr><td class="label">Alamat Rumah</td><td class="sep">:</td>
                        <td>{{ $surat->alamat_rumah ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <p class="paragraf">
                Bersedia menerima siswa tersebut di atas sebagai siswa di
                <strong>{{ $settings['nama_sekolah'] ?? '-' }}</strong>
                Kecamatan {{ $settings['kecamatan'] ?? '-' }}
                {{ $settings['kota'] ?? '-' }} dengan syarat sebagai berikut:
            </p>

            <div class="syarat-list">
                <table class="indent-table">
                    <tr>
                        <td class="indent-cell">&nbsp;</td>
                        <td>
                            <table class="list-table">
                                @foreach (\App\Models\SyaratPindahan::getActiveList() as $i => $item)
                                    <tr>
                                        <td class="syarat-no">{{ $i + 1 }}.</td>
                                        <td class="syarat-isi">{{ $item }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

            <p class="paragraf">
                Demikian surat keterangan ini dibuat dengan sebenarnya, dan dapat dipergunakan
                sebagaimana mestinya.
            </p>
        </div>

        {{-- Tanda Tangan --}}
        <div class="ttd-container">
            <p class="ttd-tempat">{{ $settings['kota'] ?? '' }},
                {{ $surat->tanggal_surat->translatedFormat('d F Y') }}</p>
            <p class="ttd-jabatan">Kepala Sekolah</p>

            <div class="stempel-ttd">
                @if (!empty($settings['stempel_path']))
                    <img src="{{ public_path('storage/' . $settings['stempel_path']) }}" class="stempel" alt="Stempel">
                @endif
                @if (!empty($settings['ttd_kepala_path']))
                    <img src="{{ public_path('storage/' . $settings['ttd_kepala_path']) }}" style="height: 50px;"
                        alt="TTD">
                @endif
            </div>

            <p class="ttd-nama">{{ $settings['nama_kepala'] ?? 'Kepala Sekolah' }}</p>
            <p class="ttd-nip">NIP. {{ $settings['nip_kepala'] ?? '-' }}</p>
        </div>

        <div class="clear"></div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>
