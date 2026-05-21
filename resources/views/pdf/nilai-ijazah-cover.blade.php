<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cover Ijazah - {{ $tahunAjaran->nama_tahun_ajaran }}</title>
    <style>
        @page {
            size: 215mm 330mm;
            /* F4 */
            margin: 0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 0;
            color: #1a1a1a;
            font-size: 11pt;
            line-height: 1.4;
        }

        .page {
            position: relative;
            width: 215mm;
            height: 330mm;
            page-break-after: always;
            overflow: hidden;
        }

        .page:last-child {
            page-break-after: auto;
        }

        /* ================= Border Ornamental ================= */
        .border-outer {
            position: absolute;
            top: 5mm;
            left: 5mm;
            right: 5mm;
            bottom: 5mm;
            border: 3pt solid #2d6b3f;
            z-index: 0;
        }

        .border-inner {
            position: absolute;
            top: 8mm;
            left: 8mm;
            right: 8mm;
            bottom: 8mm;
            border-top: 1.5pt solid #2d6b3f;
            border-left: 1.5pt solid #2d6b3f;
            border-right: 1.5pt solid #2d6b3f;
            border-bottom: none;
            z-index: 0;
        }

        .border-pattern-top,
        .border-pattern-bottom {
            position: absolute;
            left: 6mm;
            right: 6mm;
            height: 6mm;
            background: repeating-linear-gradient(90deg,
                    #3a7d4e 0px, #3a7d4e 3px,
                    #8fbc8f 3px, #8fbc8f 6px,
                    #2d6b3f 6px, #2d6b3f 9px,
                    #a8d5a8 9px, #a8d5a8 12px);
            opacity: 0.4;
        }

        .border-pattern-top {
            top: 5.5mm;
        }

        .border-pattern-bottom {
            bottom: 5.5mm;
        }

        .border-pattern-left,
        .border-pattern-right {
            position: absolute;
            top: 6mm;
            bottom: 6mm;
            width: 6mm;
            background: repeating-linear-gradient(0deg,
                    #3a7d4e 0px, #3a7d4e 3px,
                    #8fbc8f 3px, #8fbc8f 6px,
                    #2d6b3f 6px, #2d6b3f 9px,
                    #a8d5a8 9px, #a8d5a8 12px);
            opacity: 0.4;
        }

        .border-pattern-left {
            left: 5.5mm;
        }

        .border-pattern-right {
            right: 5.5mm;
        }

        /* ================= Content Area ================= */
        .content {
            position: absolute;
            top: 12mm;
            left: 12mm;
            right: 12mm;
            bottom: 12mm;
            padding: 8mm 10mm;
        }

        /* ================= Watermark ================= */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100mm;
            height: 100mm;
            z-index: 0;
            pointer-events: none;
            opacity: 0.06;
        }

        .watermark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* ================= Header ================= */
        .header-section {
            text-align: center;
            position: relative;
            z-index: 1;
            margin-bottom: 3mm;
        }

        .garuda-img {
            height: 18mm;
            margin-bottom: 2mm;
        }

        .kementerian-text {
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 1mm;
        }

        .republik-text {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 1.5px;
            margin-bottom: 4mm;
        }

        .judul-ijazah {
            font-size: 32pt;
            font-weight: bold;
            letter-spacing: 10px;
            margin-bottom: 3mm;
        }

        .jenjang-text {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 2px;
            color: #1a1a1a;
            margin-bottom: 1mm;
        }

        .tahun-ajaran-text {
            font-size: 11pt;
            margin-bottom: 5mm;
        }

        /* ================= Nomor Ijazah ================= */
        .nomor-section {
            text-align: center;
            margin-bottom: 4mm;
            position: relative;
            z-index: 1;
        }

        .nomor-section .label {
            font-size: 11pt;
            font-style: italic;
        }

        .nomor-dots {
            display: inline-block;
            border-bottom: 1px dotted #333;
            width: 70mm;
            min-height: 1em;
            vertical-align: bottom;
        }

        /* ================= Body Fields ================= */
        .body-section {
            position: relative;
            z-index: 1;
            padding: 0 5mm;
        }

        .intro-text {
            font-size: 11pt;
            font-style: italic;
            margin-bottom: 3mm;
        }

        .field-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2mm;
        }

        .field-table td {
            padding: 1.5mm 0;
            vertical-align: top;
            font-size: 11pt;
        }

        .field-table .lbl {
            width: 58mm;
        }

        .field-table .colon {
            width: 4mm;
        }

        .field-table .val {
            border-bottom: 1px dotted #444;
            padding-left: 2mm;
        }

        .footer-section .field-table .val,
        .footer-table .val {
            border-bottom: none;
        }

        .menerangkan-text {
            font-size: 11pt;
            font-style: italic;
            margin: 3mm 0 2mm 0;
        }

        /* ================= LULUS ================= */
        .lulus-section {
            text-align: center;
            margin: 6mm 0 3mm 0;
            position: relative;
            z-index: 1;
        }

        .lulus-text {
            font-size: 24pt;
            font-weight: bold;
            font-style: italic;
            letter-spacing: 6px;
        }

        /* ================= Paragraph ================= */
        .keterangan-section {
            position: relative;
            z-index: 1;
            padding: 0 5mm;
            margin-bottom: 4mm;
        }

        .keterangan-text {
            font-size: 10.5pt;
            text-align: justify;
            line-height: 1.6;
        }

        /* ================= Footer ================= */
        .footer-section {
            position: absolute;
            bottom: 12mm;
            left: 20mm;
            right: 20mm;
            z-index: 10;
        }

        .footer-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: none;
        }

        .footer-table tr,
        .footer-table td {
            vertical-align: top;
            border: none;
            border-top: none;
            border-bottom: none;
            border-left: none;
            border-right: none;
            padding: 0;
        }

        .foto-area {
            width: 30mm;
            height: 40mm;
            border: 1px solid #666;
            text-align: center;
            font-size: 8pt;
            color: #666;
            padding-top: 14mm;
            line-height: 1.4;
        }

        .ttd-area {
            text-align: left;
            padding-left: 15mm;
            font-size: 11pt;
        }

        .ttd-area .tempat-tanggal {
            margin-bottom: 1mm;
        }

        .ttd-area .jabatan {
            margin-bottom: 0;
        }

        .ttd-space {
            height: 20mm;
            border: none;
        }

        .ttd-area .nama-pejabat {
            font-weight: bold;
            border-bottom: 1px solid #333;
            display: inline-block;
            min-width: 60mm;
            padding-bottom: 1mm;
        }

        .ttd-area .nip-text {
            font-size: 10pt;
            margin-top: 1mm;
        }

        /* ================= Serial Number ================= */
        .serial-section {
            position: absolute;
            bottom: 6mm;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 2px;
            z-index: 1;
        }

        /* ================= Corner Ornaments ================= */
        .corner {
            position: absolute;
            width: 12mm;
            height: 12mm;
            z-index: 1;
        }

        .corner-tl {
            top: 9mm;
            left: 9mm;
        }

        .corner-tr {
            top: 9mm;
            right: 9mm;
        }

        .corner-bl {
            bottom: 9mm;
            left: 9mm;
        }

        .corner-br {
            bottom: 9mm;
            right: 9mm;
        }

        .corner svg {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>
    @php
        $namaSekolah = $settings['nama_sekolah'] ?? 'Nama Madrasah';
        $alamatSekolah = $settings['alamat'] ?? '';
        $npsn = $settings['npsn'] ?? '';
        $kepalaSekolah = $settings['nama_kepala'] ?? '';
        $nipKepala = $settings['nip_kepala'] ?? '';
        $kabupaten = $settings['kota'] ?? '';
        $provinsi = $settings['provinsi'] ?? '';
        $kotaSurat = $kabupaten ?: '';
        \Carbon\Carbon::setLocale('id');

        $tahunParts = explode('/', $tahunAjaran->nama_tahun_ajaran);
        $tahunKiri = $tahunParts[0] ?? '';
        $tahunKanan = $tahunParts[1] ?? '';
    @endphp

    @foreach ($siswas as $siswa)
        <div class="page">
            {{-- Border ornamental --}}
            <div class="border-outer"></div>
            <div class="border-inner"></div>
            <div class="border-pattern-top"></div>
            <div class="border-pattern-bottom"></div>
            <div class="border-pattern-left"></div>
            <div class="border-pattern-right"></div>

            {{-- Watermark --}}
            <div class="watermark">
                @if (file_exists(public_path('img/garuda.png')))
                    <img src="{{ public_path('img/garuda.png') }}" alt="">
                @elseif (file_exists(public_path('img/logo-sekolah.png')))
                    <img src="{{ public_path('img/logo-sekolah.png') }}" alt="">
                @endif
            </div>

            {{-- Content --}}
            <div class="content">
                {{-- Header --}}
                <div class="header-section">
                    @if (file_exists(public_path('img/garuda.png')))
                        <img src="{{ public_path('img/garuda.png') }}" class="garuda-img" alt="Garuda Pancasila">
                    @else
                        <div
                            style="height:18mm;display:inline-block;width:18mm;border:1px dashed #999;line-height:18mm;font-size:8pt;color:#999;margin-bottom:2mm;">
                            GARUDA</div>
                    @endif
                    <br>
                    <div class="kementerian-text">KEMENTERIAN AGAMA</div>
                    <div class="republik-text">REPUBLIK INDONESIA</div>
                    <div class="judul-ijazah">I J A Z A H</div>
                    <div class="jenjang-text">MADRASAH IBTIDAIYAH</div>
                    <div class="tahun-ajaran-text">TAHUN AJARAN {{ $tahunKiri }}/{{ $tahunKanan }}</div>
                </div>

                {{-- Nomor --}}
                <div class="nomor-section">
                    <span class="label">Nomor:</span> <span class="nomor-dots"></span>
                </div>

                {{-- Body --}}
                <div class="body-section">
                    <div class="intro-text">Yang bertanda tangan di bawah ini, Kepala:</div>

                    <table class="field-table">
                        <tr>
                            <td class="lbl">Nomor Pokok Sekolah Nasional</td>
                            <td class="colon">:</td>
                            <td class="val">{{ $npsn ?: '' }}</td>
                        </tr>
                        <tr>
                            <td class="lbl">Kabupaten/Kota</td>
                            <td class="colon">:</td>
                            <td class="val">{{ $kabupaten ?: '' }}</td>
                        </tr>
                        <tr>
                            <td class="lbl">Provinsi</td>
                            <td class="colon">:</td>
                            <td class="val">{{ $provinsi ?: '' }}</td>
                        </tr>
                    </table>

                    <div class="menerangkan-text">Menerangkan bahwa:</div>

                    <table class="field-table">
                        <tr>
                            <td class="lbl">Nama</td>
                            <td class="colon">:</td>
                            <td class="val" style="font-weight: bold; font-size: 12pt;">{{ $siswa->nama_lengkap }}
                            </td>
                        </tr>
                        <tr>
                            <td class="lbl">Tempat dan tanggal lahir</td>
                            <td class="colon">:</td>
                            <td class="val">{{ $siswa->tempat_lahir ?: '' }}@if ($siswa->tanggal_lahir)
                                    , {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="lbl">Nama orang tua/wali</td>
                            <td class="colon">:</td>
                            <td class="val">{{ $siswa->nama_ayah_kandung ?: ($siswa->nama_wali ?: '') }}</td>
                        </tr>
                        <tr>
                            <td class="lbl">Nomor Induk Kependudukan</td>
                            <td class="colon">:</td>
                            <td class="val">{{ $siswa->nik ?: '' }}</td>
                        </tr>
                        <tr>
                            <td class="lbl">Nomor Induk Siswa Nasional</td>
                            <td class="colon">:</td>
                            <td class="val">{{ $siswa->nisn ?: '' }}</td>
                        </tr>
                    </table>
                </div>

                {{-- LULUS --}}
                <div class="lulus-section">
                    <span class="lulus-text">LULUS</span>
                </div>

                {{-- Keterangan --}}
                <div class="keterangan-section">
                    <p class="keterangan-text">
                        dari satuan pendidikan setelah memenuhi seluruh kriteria sesuai dengan peraturan
                        perundang-undangan.
                    </p>
                </div>
            </div>

            {{-- Footer: foto + tanda tangan (di luar .content, langsung di .page) --}}
            <div class="footer-section">
                <table class="footer-table">
                    <tr>
                        <td style="width: 40%; text-align: center; vertical-align: bottom; border: none;">
                            <div class="foto-area">
                                Pas Foto<br>3 &times; 4 cm
                            </div>
                        </td>
                        <td style="width: 60%; border: none;">
                            <div class="ttd-area">
                                <div class="tempat-tanggal">
                                    {{ $kotaSurat ? $kotaSurat . ', ' : '' }}{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                                </div>
                                <div class="jabatan">Kepala,</div>
                                <div class="ttd-space"></div>
                                <div class="nama-pejabat">{{ $kepalaSekolah ?: '' }}</div>
                                <div class="nip-text">NIP. {{ $nipKepala ?: '' }}</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Serial number --}}
            <div class="serial-section">
                MI 24 &nbsp;&nbsp; 0000000000
            </div>
        </div>
    @endforeach
</body>

</html>
