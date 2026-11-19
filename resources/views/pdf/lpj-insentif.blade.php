<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>LPJ Insentif {{ $lpj->periode }} {{ $lpj->tahun }}</title>
    @php
        // Ukuran dasar untuk F4 landscape; $scale < 1 hanya dipakai bila baris terlalu banyak untuk 1 halaman.
        $s = $scale ?? 1;
        $pt = fn (float $v) => round($v * $s, 2).'pt';
        $px = fn (float $v) => round($v * $s, 1).'px';
        $rp = fn (int $v) => number_format($v, 0, ',', '.');
        $rows = $lpj->penerimas;
    @endphp
    <style>
        @page {
            margin: 10mm 10mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: {{ $pt(8) }};
            line-height: 1.2;
            color: #000;
            margin: 0;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: {{ $pt(9.5) }};
            margin-bottom: {{ $px(8) }};
        }

        .judul p {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table.tabel th,
        table.tabel td {
            border: 0.75px solid #000;
            padding: {{ $px(1.5) }} {{ $px(2.5) }};
            vertical-align: middle;
            overflow: hidden;
        }

        table.tabel th {
            text-align: center;
            font-weight: normal;
            font-size: {{ $pt(7.5) }};
        }

        table.tabel tbody td {
            height: {{ $px(19) }};
        }

        .c {
            text-align: center;
        }

        .r {
            text-align: right;
        }

        .l {
            text-align: left;
        }

        .nw {
            white-space: nowrap;
        }

        .id {
            white-space: nowrap;
            font-size: {{ $pt(7.5) }};
        }

        tfoot td {
            font-weight: bold;
        }

        table.ttd {
            margin-top: {{ $px(10) }};
        }

        table.ttd td {
            vertical-align: top;
            white-space: nowrap;
            padding: 0;
        }

        .ttd-space {
            height: {{ $px(42) }};
        }
    </style>
</head>

<body>
    <div class="judul">
        <p>{{ strtoupper($settings['nama_sekolah'] ?? 'MI DAARUL HIKMAH') }}</p>
        <p>KECAMATAN {{ strtoupper(trim($settings['kecamatan'] ?? '') ?: 'NEGLASARI') }}</p>
        <p>BULAN {{ strtoupper($lpj->periode) }} {{ $lpj->tahun }}</p>
    </div>

    {{-- Lebar kolom (%) di <th> (dompdf mengabaikan <col>), disusun agar total 100% dan NPWP/NIP 16–18 digit muat 1 baris di F4 landscape. --}}
    <table class="tabel">
        <thead>
            <tr>
                <th rowspan="2" style="width:2.5%">NO.</th>
                <th rowspan="2" style="width:8.5%">NPWP</th>
                <th rowspan="2" style="width:11.5%">NAMA PEGAWAI</th>
                <th rowspan="2" style="width:9%">NIP/PegId/NUPTK</th>
                <th rowspan="2" style="width:5.3%">PANGKAT</th>
                <th rowspan="2" style="width:3%">GOL.</th>
                <th colspan="3" style="width:9%">STATUS PEGAWAI</th>
                <th rowspan="2" style="width:7.5%">JABATAN</th>
                <th rowspan="2" style="width:5.5%">TARIF</th>
                <th rowspan="2" style="width:4.2%">VOLUME</th>
                <th rowspan="2" style="width:4%">SATUAN</th>
                <th rowspan="2" style="width:6.3%">JUMLAH UANG</th>
                <th rowspan="2" style="width:4.5%">PPH21</th>
                <th rowspan="2" style="width:6.2%">JUMLAH<br>DITERIMA<br>(TRANSFER)</th>
                <th rowspan="2" style="width:7%">NO.REKENING</th>
                <th rowspan="2" style="width:6%">TANDA TANGAN</th>
            </tr>
            <tr>
                <th style="width:3%">PNS</th>
                <th style="width:3%">PPPK</th>
                <th style="width:3%">NON<br>PNS</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $p)
                <tr>
                    <td class="c">{{ $loop->iteration }}</td>
                    <td class="id">{{ $p->npwp }}</td>
                    <td>{{ $p->nama }}</td>
                    <td class="id">{{ $p->nip_pegid_nuptk }}</td>
                    <td>{{ $p->pangkat }}</td>
                    <td>{{ $p->golongan }}</td>
                    <td class="c">{{ $p->status_pegawai === 'PNS' ? '1' : '' }}</td>
                    <td class="c">{{ $p->status_pegawai === 'PPPK' ? '1' : '' }}</td>
                    <td class="c">{{ $p->status_pegawai === 'NON PNS' ? '1' : '' }}</td>
                    <td>{{ $p->jabatan }}</td>
                    <td class="r nw">{{ $rp($p->tarif) }}</td>
                    <td class="c">{{ $p->volume }}</td>
                    <td>{{ $p->satuan }}</td>
                    <td class="r nw">{{ $rp($p->jumlah_uang) }}</td>
                    <td class="r nw">{{ $p->pph21 > 0 ? $rp($p->pph21) : '' }}</td>
                    <td class="r nw">{{ $rp($p->jumlah_diterima) }}</td>
                    <td class="id">{{ $p->no_rekening }}</td>
                    {{-- Zig-zag: nomor ganjil di kanan, genap di kiri --}}
                    <td class="{{ $loop->odd ? 'r' : 'l' }}">{{ $loop->iteration }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="18" class="c">Belum ada data penerima.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td>JUMLAH</td>
                <td colspan="7"></td>
                <td class="r nw">{{ $rp((int) $rows->sum('tarif')) }}</td>
                <td colspan="2"></td>
                <td class="r nw">{{ $rp((int) $rows->sum(fn ($p) => $p->jumlah_uang)) }}</td>
                <td></td>
                <td class="r nw">{{ $rp((int) $rows->sum(fn ($p) => $p->jumlah_diterima)) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    {{-- Kiri sejajar kolom NAMA PEGAWAI (offset 11%), kanan sejajar kolom JUMLAH DITERIMA (offset 80.8%). --}}
    <table class="ttd">
        <tr>
            <td style="width:11%"></td>
            <td style="width:69.8%">
                MENGETAHUI/MENYETUJUI :<br>
                {{ strtoupper($lpj->jabatan_kepala) }}
                <div class="ttd-space"></div>
                {{ $lpj->nama_kepala }}
            </td>
            <td style="width:19.2%">
                {{ strtoupper($lpj->kota) }}, &nbsp;&nbsp; {{ $lpj->bulan_ttd }} {{ $lpj->tahun }}<br>
                {{ strtoupper($lpj->jabatan_bendahara) }}
                <div class="ttd-space"></div>
                {{ $lpj->nama_bendahara }}
            </td>
        </tr>
    </table>
</body>

</html>
