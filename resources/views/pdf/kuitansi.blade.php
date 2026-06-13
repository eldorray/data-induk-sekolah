<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Kuitansi / Bukti Pembayaran</title>
    <style>
        @page {
            margin: 1cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000;
        }

        .kuitansi-page {
            page-break-after: always;
        }

        .kuitansi-page.last {
            page-break-after: auto;
        }

        .box {
            border: 1.5px solid #000;
            padding: 24px 34px;
            height: 815pt;
        }

        .judul {
            text-align: center;
            font-size: 15pt;
            font-weight: bold;
            margin: 0 0 26px 0;
        }

        table {
            border-collapse: collapse;
        }

        /* Blok kanan: Tahun Anggaran & Nomor Bukti */
        .head-table {
            width: 100%;
            margin-bottom: 34px;
        }

        .head-table > tbody > tr > td {
            width: 50%;
            vertical-align: top;
        }

        .head-inner td {
            padding: 1px 0;
            font-size: 12pt;
            vertical-align: top;
        }

        .head-inner td.lbl {
            width: 130px;
        }

        .head-inner td.sep {
            width: 12px;
        }

        /* Tabel data utama */
        .data-table td {
            padding: 2px 0;
            font-size: 12pt;
            vertical-align: top;
        }

        .data-table td.lbl {
            width: 210px;
        }

        /* Area tanda tangan */
        .sig-table {
            width: 100%;
        }

        .sig-table td {
            width: 50%;
            vertical-align: top;
            font-size: 12pt;
        }

        .sig-space {
            height: 95px;
        }

        .nm {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    @php
        $namaMadrasah = $settings['kuitansi_nama_madrasah'] ?? '';
        $desaKecamatan = $settings['kuitansi_desa_kecamatan'] ?? '';
        $kabupaten = $settings['kuitansi_kabupaten'] ?? '';
        $provinsi = $settings['kuitansi_provinsi'] ?? '';
        $sumberDana = $settings['kuitansi_sumber_dana'] ?? '';
        $sudahTerimaDari = $settings['kuitansi_sudah_terima_dari'] ?? '';
        $kepala = $settings['kuitansi_kepala_madrasah'] ?? '';
        $bendahara = $settings['kuitansi_bendahara_madrasah'] ?? '';
    @endphp

    @foreach ($kuitansis as $k)
        <div class="kuitansi-page {{ $loop->last ? 'last' : '' }}">
            <div class="box">
                <div class="judul">KUITANSI/BUKTI PEMBAYARAN</div>

                {{-- Tahun Anggaran & Nomor Bukti (blok kanan) --}}
                <table class="head-table">
                    <tr>
                        <td></td>
                        <td>
                            <table class="head-inner">
                                <tr>
                                    <td class="lbl">Tahun Anggaran</td>
                                    <td class="sep">:</td>
                                    <td>{{ $k->tahun_anggaran ?? ($settings['kuitansi_tahun_anggaran'] ?? date('Y')) }}</td>
                                </tr>
                                <tr>
                                    <td class="lbl">Nomor Bukti</td>
                                    <td class="sep">:</td>
                                    <td>{{ $k->nomor_bukti_lengkap }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                {{-- Data utama --}}
                <table class="data-table">
                    <tr>
                        <td class="lbl">Sudah terima dari</td>
                        <td>: {{ $sudahTerimaDari }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Madrasah</td>
                        <td>: {{ $namaMadrasah }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Desa/Kecamatan</td>
                        <td>: {{ $desaKecamatan }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Kabupaten</td>
                        <td>: {{ $kabupaten }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Provinsi</td>
                        <td>: {{ $provinsi }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Jumlah Uang</td>
                        <td>: {{ $k->jumlah_format }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Terbilang</td>
                        <td>: {{ $k->terbilang }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Untuk Pembayaran</td>
                        <td>: {{ $k->uraian_pembayaran }}</td>
                    </tr>
                    <tr>
                        <td class="lbl">Sumber Dana</td>
                        <td>: {{ $sumberDana }}</td>
                    </tr>
                </table>

                {{-- Penerima Uang (blok kanan) --}}
                <table class="sig-table" style="margin-top: 60px;">
                    <tr>
                        <td></td>
                        <td>Penerima Uang<br>Tanda Tangan</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div class="sig-space"></div>
                            <span class="nm">({{ $k->penerima }})</span>
                        </td>
                    </tr>
                </table>

                {{-- Lunas dibayar tanggal (blok kanan) --}}
                <table class="sig-table" style="margin-top: 18px;">
                    <tr>
                        <td></td>
                        <td>Lunas dibayar tanggal {{ $k->tanggal_lunas->format('d-m-Y') }}</td>
                    </tr>
                </table>

                {{-- Kepala (kiri) & Bendahara (kanan) --}}
                <table class="sig-table" style="margin-top: 2px;">
                    <tr>
                        <td>Kepala Madrasah<br>Tanda Tangan</td>
                        <td>Bendahara Madrasah<br>Tanda Tangan</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="sig-space"></div>
                            <span class="nm">({{ $kepala }})</span>
                        </td>
                        <td>
                            <div class="sig-space"></div>
                            <span class="nm">({{ $bendahara }})</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endforeach
</body>

</html>
