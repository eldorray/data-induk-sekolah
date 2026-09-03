<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Pernyataan Rombongan Belajar</title>
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
            margin: 5px 0 15px 0;
            padding: 1px 0;
        }

        .judul-surat {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul-surat h2 {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
        }

        .judul-surat p {
            font-size: 11pt;
            margin: 3px 0 0 0;
        }

        .sub-judul {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            margin: 0 0 20px 0;
            line-height: 1.4;
        }

        table.rekap {
            border-collapse: collapse;
            width: 100%;
            margin: 0 auto;
            font-size: 10pt;
        }

        table.rekap th,
        table.rekap td {
            border: 1px solid #000;
            padding: 5px 2px;
            text-align: center;
            vertical-align: middle;
        }

        table.rekap th {
            font-weight: bold;
        }

        .ttd-wrapper {
            margin-top: 60px;
            width: 100%;
        }

        .ttd-wrapper table {
            width: 100%;
            border-collapse: collapse;
        }

        .ttd-wrapper td {
            width: 50%;
            vertical-align: top;
            text-align: left;
        }

        .ttd-wrapper td.kanan {
            text-align: left;
            padding-left: 30px;
        }

        .ttd-baris {
            margin: 0;
            line-height: 1.5;
        }

        .ttd-space {
            height: 75px;
            position: relative;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
        }

        .ttd-nip {
            margin: 0;
        }
    </style>
</head>

<body>
    {{-- Kop Surat --}}
    <div class="kop-surat">
        @if (!empty($settings['kop_surat_path']))
            <img src="{{ public_path('storage/' . $settings['kop_surat_path']) }}" alt="Kop Surat">
        @else
            <h2 style="font-size: 16pt; margin: 0;">{{ $settings['nama_yayasan'] ?? 'YAYASAN PENDIDIKAN' }}</h2>
            <h1 style="font-size: 18pt; margin: 5px 0;">{{ $namaMadrasah }}</h1>
            <p style="font-size: 10pt; margin: 0;">
                <strong>NSM:</strong> {{ $settings['nsm'] ?? '' }} &nbsp;&nbsp;&nbsp;
                <strong>NPSN:</strong> {{ $settings['npsn'] ?? '' }}
            </p>
        @endif
    </div>
    <div class="garis-kop"></div>

    {{-- Judul --}}
    <div class="judul-surat">
        <h2>SURAT PERNYATAAN</h2>
        <p>Nomor : {{ $nomorSurat }}</p>
    </div>

    <div class="sub-judul">
        REKAPITULASI JUMLAH ROMBONGAN BELAJAR<br>
        {{ strtoupper($namaMadrasah) }}<br>
        TAHUN PELAJARAN {{ $tahunPelajaran }}
    </div>

    {{-- Tabel Rekap --}}
    <table class="rekap">
        <thead>
            <tr>
                <th colspan="7">JUMLAH ROMBONGAN BELAJAR</th>
                <th colspan="8">JUMLAH SISWA</th>
            </tr>
            <tr>
                <th colspan="6">KELAS</th>
                <th rowspan="2">JML</th>
                <th rowspan="2">L/P</th>
                <th colspan="6">KELAS</th>
                <th rowspan="2">JUMLAH</th>
            </tr>
            <tr>
                @foreach ($rekap['tingkat'] as $label => $row)
                    <th width="5%">{{ $label }}</th>
                @endforeach
                @foreach ($rekap['tingkat'] as $label => $row)
                    <th width="5%">{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach ($rekap['tingkat'] as $row)
                    <td rowspan="2">{{ $row['rombel'] }}</td>
                @endforeach
                <td rowspan="2">{{ $rekap['total']['rombel'] }}</td>
                <td>L/P</td>
                @foreach ($rekap['tingkat'] as $row)
                    <td>{{ $row['total'] }}</td>
                @endforeach
                <td>{{ $rekap['total']['total'] }}</td>
            </tr>
            <tr>
                <td>JUMLAH</td>
                @foreach ($rekap['tingkat'] as $row)
                    <td>{{ $row['total'] }}</td>
                @endforeach
                <td>{{ $rekap['total']['total'] }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <div class="ttd-wrapper">
        <table>
            <tr>
                <td>
                    <p class="ttd-baris">Mengetahui</p>
                    <p class="ttd-baris"><em>Pengawas</em></p>
                    <div class="ttd-space"></div>
                    <p class="ttd-nama">{{ $namaPengawas ?: '.....................................' }}</p>
                    <p class="ttd-nip">NIP. {{ $nipPengawas ?: '..............................' }}</p>
                </td>
                <td class="kanan">
                    <p class="ttd-baris">{{ $kota }}, {{ $tanggalSurat }}</p>
                    <p class="ttd-baris">Kepala {{ $namaMadrasah }}</p>
                    <div class="ttd-space">
                        @if (!empty($settings['stempel_path']))
                            <img src="{{ public_path('storage/' . $settings['stempel_path']) }}"
                                style="height: 75px; position: absolute; left: 0; top: 0;" alt="Stempel">
                        @endif
                        @if (!empty($settings['ttd_kepala_path']))
                            <img src="{{ public_path('storage/' . $settings['ttd_kepala_path']) }}"
                                style="height: 65px; position: absolute; left: 20px; top: 5px;" alt="TTD">
                        @endif
                    </div>
                    <p class="ttd-nama">{{ $namaKepala }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
