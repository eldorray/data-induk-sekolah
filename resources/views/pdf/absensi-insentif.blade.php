<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daftar Hadir Guru dan Karyawan Pendidikan</title>
    <style>
        @page {
            margin: 12px 12px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8pt;
            line-height: 1.15;
            color: #000;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .judul p {
            margin: 0;
        }

        .bulan-label {
            margin: 4px 0 8px 0;
            font-weight: bold;
            font-size: 9pt;
        }

        table.tabel {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table.tabel th,
        table.tabel td {
            border: 1px solid #000;
            padding: 3px 4px;
            vertical-align: middle;
        }

        table.tabel th {
            text-align: center;
            font-weight: bold;
            background: #fff;
        }

        td.center {
            text-align: center;
        }

        /* Sel hari Minggu / libur */
        td.libur {
            background: #d9d9d9;
        }

        th.libur {
            background: #d9d9d9;
        }

        td.id-num {
            font-family: 'Courier New', Courier, monospace;
            font-size: 7pt;
            word-break: keep-all;
            white-space: nowrap;
            overflow: hidden;
        }

        .ttd-table {
            width: 100%;
            margin-top: 18px;
            border-collapse: collapse;
        }

        .ttd-table td {
            vertical-align: top;
            text-align: center;
            width: 50%;
        }

        .ttd-space {
            height: 65px;
        }
    </style>
</head>

<body>
    <div class="judul">
        <p>DAFTAR HADIR GURU DAN KARYAWAN PENDIDIKAN</p>
        <p>{{ strtoupper($settings['nama_sekolah'] ?? 'MI DAARUL HIKMAH') }}</p>
        <p>TAHUN AJARAN {{ $tahunAjaran }}</p>
    </div>

    <p class="bulan-label">BULAN&nbsp;&nbsp;: {{ ucfirst(strtolower($namaBulan)) }}</p>

    <table class="tabel">
        <thead>
            <tr>
                <th rowspan="2" style="width:2.5%;">No</th>
                <th rowspan="2" style="width:13%;">Nama</th>
                <th rowspan="2" style="width:10%;">NUPTK/PegID</th>
                <th rowspan="2" style="width:8%;">Jabatan</th>
                <th colspan="{{ $jumlahHari }}">Tanggal</th>
            </tr>
            <tr>
                @foreach (range(1, $jumlahHari) as $d)
                    <th class="{{ isset($arsir[$d]) ? 'libur' : '' }}"
                        style="width:{{ round(66.5 / $jumlahHari, 2) }}%;">{{ $d }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($lpj->penerimas as $p)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td>{{ $p->nama }}</td>
                    <td class="id-num">{{ $p->nip_pegid_nuptk ?? '-' }}</td>
                    <td class="center">{{ $p->jabatan }}</td>
                    @foreach (range(1, $jumlahHari) as $d)
                        <td class="{{ isset($arsir[$d]) ? 'libur' : '' }}">&nbsp;</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 4 + $jumlahHari }}" class="center">Belum ada data penerima.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="ttd-table">
        <tr>
            <td></td>
            <td>
                <p style="margin:0 0 2px 0;">{{ ucwords(strtolower($lpj->kota)) }}, {{ $jumlahHari }}
                    {{ ucfirst(strtolower($namaBulan)) }} {{ $tahun }}</p>
                <p style="margin:0;">Kepala {{ $settings['nama_sekolah'] ?? 'MI Daarul Hikmah' }}</p>
                <div class="ttd-space"></div>
                <p style="margin:0;font-weight:bold;text-decoration:underline;">{{ $lpj->nama_kepala }}</p>
            </td>
        </tr>
    </table>
</body>

</html>
