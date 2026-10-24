<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daftar Penerima Insentif</title>
    @php
        $s = $scale ?? 1;
        // Base kompak: pada scale 1.0 pun hampir muat 1 halaman untuk 21 baris,
        // sehingga auto-fit tidak perlu meng-ciutkan agresif dan padding/tinggi
        // tetap terlihat lega di hasil cetak.
        $base = round(7.5 * $s, 2);
        $small = round(6.5 * $s, 2);
    @endphp
    <style>
        @page {
            margin: {{ max(10, round(12 * $s)) }}px {{ max(10, round(12 * $s)) }}px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: {{ $base }}pt;
            line-height: 1.15;
            color: #000;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            margin-bottom: {{ max(2, round(8 * $s)) }}px;
            font-size: {{ round(8.5 * $s, 2) }}pt;
        }

        .judul p {
            margin: 0;
        }

        table.tabel {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table.tabel th,
        table.tabel td {
            border: 1px solid #000;
            padding: {{ round(1.5 * $s, 1) }}px {{ round(2.5 * $s, 1) }}px;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        table.tabel th {
            text-align: center;
            font-weight: bold;
            background: #fff;
            line-height: 1.15;
            font-size: {{ round(7 * $s, 2) }}pt;
        }

        td.num {
            text-align: right;
            white-space: nowrap;
        }

        td.center {
            text-align: center;
        }

        tfoot td {
            font-weight: bold;
            text-align: center;
            font-size: {{ round(9.5 * $s, 2) }}pt;
            background: #d8d8d8;
            border-top: 2px solid #000 !important;
            line-height: {{ round(28 * $s) }}px;
        }

        tfoot td.num {
            font-size: {{ round(9.5 * $s, 2) }}pt;
            line-height: {{ round(28 * $s) }}px;
        }

        /* NPWP, NIP, rekening — monospace rapat agar muat 1 baris */
        td.id-num {
            font-family: 'Courier New', Courier, monospace;
            font-size: {{ $small }}pt;
            letter-spacing: -0.3px;
            word-break: keep-all;
            white-space: nowrap;
            overflow: hidden;
        }

        /* Kolom TANDA TANGAN — padding dan tinggi lebih lega */
        td.ttd-cell {
            font-weight: bold;
            font-size: {{ round(10.5 * $s, 2) }}pt;
            height: {{ round(26 * $s) }}px;
            padding-left: {{ round(28 * $s, 1) }}px;
            padding-right: {{ round(8 * $s, 1) }}px;
            padding-top: {{ round(5 * $s, 1) }}px;
            padding-bottom: {{ round(5 * $s, 1) }}px;
            text-align: left;
        }

        td.ttd-cell.top {
            vertical-align: top;
        }

        td.ttd-cell.bottom {
            vertical-align: bottom;
        }

        /* Blok tanda tangan pejabat */
        .ttd-table {
            width: 100%;
            margin-top: {{ max(6, round(12 * $s)) }}px;
            border-collapse: collapse;
        }

        .ttd-table td {
            vertical-align: top;
            width: 50%;
            padding: 0 {{ round(8 * $s) }}px;
            font-size: {{ round(9 * $s, 2) }}pt;
        }

        .ttd-table td.left {
            text-align: left;
        }

        .ttd-table td.right {
            text-align: left;
            padding-left: 55%;
        }

        .ttd-line {
            margin: 0;
            padding: 0;
        }

        .ttd-space {
            height: {{ round(40 * $s) }}px;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <div class="judul">
        <p>DAFTAR PENERIMA INSENTIF PENDIDIK DAN TENAGA KEPENDIDIKAN PNS, PPPK DAN NON PNS</p>
        <p>{{ strtoupper($settings['nama_sekolah'] ?? 'MI DAARUL HIKMAH') }}</p>
        <p>KECAMATAN {{ strtoupper(trim($settings['kecamatan'] ?? '') ?: 'NEGLASARI') }}</p>
        <p>BULAN {{ strtoupper($lpj->periode) }} {{ $lpj->tahun }}</p>
    </div>

    <table class="tabel">
        <colgroup>
            <col style="width:3%">
            <col style="width:10%">
            <col style="width:12%">
            <col style="width:9%">
            <col style="width:5%">
            <col style="width:3%">
            <col style="width:3%">
            <col style="width:3%">
            <col style="width:3%">
            <col style="width:9%">
            <col style="width:6%">
            <col style="width:4%">
            <col style="width:4%">
            <col style="width:6%">
            <col style="width:5%">
            <col style="width:6%">
            <col style="width:8%">
            <col style="width:9%">
        </colgroup>
        <thead>
            <tr>
                <th rowspan="2">NO.</th>
                <th rowspan="2">NPWP</th>
                <th rowspan="2">NAMA<br>PEGAWAI</th>
                <th rowspan="2">NIP/PegId/<br>NUPTK</th>
                <th rowspan="2">PANGKAT</th>
                <th rowspan="2">GOL.</th>
                <th colspan="3">STATUS PEGAWAI</th>
                <th rowspan="2">JABATAN</th>
                <th rowspan="2">TARIF</th>
                <th rowspan="2">VOLUME</th>
                <th rowspan="2">SATUAN</th>
                <th rowspan="2">JUMLAH<br>UANG</th>
                <th rowspan="2">PPH21</th>
                <th rowspan="2">JUMLAH<br>DITERIMA<br>(TRANSFER)</th>
                <th rowspan="2">NO.<br>REKENING</th>
                <th rowspan="2">TANDA<br>TANGAN</th>
            </tr>
            <tr>
                <th>PNS</th>
                <th>PPPK</th>
                <th>NON PNS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lpj->penerimas as $p)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td class="id-num">{{ $p->npwp ?? '' }}</td>
                    <td>{{ $p->nama }}</td>
                    <td class="id-num">{{ $p->nip_pegid_nuptk ?? '' }}</td>
                    <td>{{ $p->pangkat ?? '' }}</td>
                    <td class="center">{{ $p->golongan ?? '' }}</td>
                    <td class="center">{{ $p->status_pegawai === 'PNS' ? '1' : '' }}</td>
                    <td class="center">{{ $p->status_pegawai === 'PPPK' ? '1' : '' }}</td>
                    <td class="center">{{ $p->status_pegawai === 'NON PNS' ? '1' : '' }}</td>
                    <td>{{ $p->jabatan }}</td>
                    <td class="num">{{ number_format($p->tarif, 0, ',', '.') }}</td>
                    <td class="center">{{ $p->volume }}</td>
                    <td class="center">{{ $p->satuan }}</td>
                    <td class="num">{{ number_format($p->jumlah_uang, 0, ',', '.') }}</td>
                    <td class="num">{{ $p->pph21 > 0 ? number_format($p->pph21, 0, ',', '.') : '' }}</td>
                    <td class="num">{{ number_format($p->jumlah_diterima, 0, ',', '.') }}</td>
                    <td class="id-num">{{ $p->no_rekening ?? '' }}</td>
                    <td class="ttd-cell {{ $loop->even ? 'bottom' : 'top' }}">{{ $loop->iteration }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="18" class="center">Belum ada data penerima.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="10" style="text-align:center;">JUMLAH</td>
                <td class="num">{{ number_format($lpj->penerimas->sum('tarif'), 0, ',', '.') }}</td>
                <td class="num">{{ $lpj->penerimas->sum('volume') }}</td>
                <td></td>
                <td class="num">{{ number_format($lpj->penerimas->sum(fn ($p) => $p->jumlah_uang), 0, ',', '.') }}</td>
                <td class="num">{{ $lpj->total_pph21 > 0 ? number_format($lpj->total_pph21, 0, ',', '.') : '' }}</td>
                <td class="num">{{ number_format($lpj->penerimas->sum(fn ($p) => $p->jumlah_diterima), 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <table class="ttd-table">
        <tr>
            <td class="left">
                <p class="ttd-line">MENGETAHUI/MENYETUJUI :</p>
                <p class="ttd-line">{{ $lpj->jabatan_kepala }}</p>
                <div class="ttd-space"></div>
                <p class="ttd-nama">{{ $lpj->nama_kepala }}</p>
            </td>
            <td class="right">
                <p class="ttd-line">{{ ucwords(strtolower($lpj->kota)) }}, {{ $lpj->bulan_ttd }} {{ $lpj->tahun }}</p>
                <p class="ttd-line">{{ $lpj->jabatan_bendahara }}</p>
                <div class="ttd-space"></div>
                <p class="ttd-nama">{{ $lpj->nama_bendahara }}</p>
            </td>
        </tr>
    </table>
</body>

</html>
