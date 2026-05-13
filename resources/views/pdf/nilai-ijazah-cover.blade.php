<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cover Ijazah - {{ $tahunAjaran->nama_tahun_ajaran }}</title>
    <style>
        @page {
            size: 215mm 330mm; /* F4 */
            margin: 0;
        }

        * { box-sizing: border-box; }

        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 0;
            color: #111;
            font-size: 11pt;
            line-height: 1.35;
        }

        .page {
            position: relative;
            width: 215mm;
            height: 330mm;
            padding: 15mm 18mm 15mm 18mm;
            page-break-after: always;
            overflow: hidden;
        }

        .page:last-child { page-break-after: auto; }

        /* ================= Header ================= */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 8mm;
        }
        .header-left, .header-right {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }
        .header-right {
            text-align: right;
            font-size: 10pt;
        }
        .logo-kemenag {
            display: inline-block;
            vertical-align: middle;
        }
        .logo-kemenag img {
            width: 14mm;
            height: 14mm;
            vertical-align: middle;
        }
        .nomenklatur-mini {
            display: inline-block;
            vertical-align: middle;
            margin-left: 3mm;
            font-size: 7pt;
            font-weight: bold;
            line-height: 1.15;
            text-transform: uppercase;
            max-width: 60mm;
        }
        .nomor-ijazah {
            font-size: 10pt;
            font-weight: normal;
        }
        .nomor-ijazah .dots {
            border-bottom: 1px dotted #333;
            display: inline-block;
            width: 55mm;
            min-height: 1em;
            vertical-align: bottom;
        }

        /* Garuda tengah */
        .garuda-wrap {
            text-align: center;
            margin: 2mm 0 2mm 0;
        }
        .garuda-wrap img {
            height: 22mm;
        }

        .nomenklatur-main {
            text-align: center;
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.3;
            margin: 0 10mm 6mm 10mm;
        }

        .judul-ijazah {
            text-align: center;
            font-size: 30pt;
            font-weight: bold;
            letter-spacing: 12px;
            margin: 4mm 0 3mm 0;
        }

        .tahun-ajaran-line {
            text-align: center;
            font-size: 12pt;
            margin-bottom: 6mm;
        }
        .tahun-ajaran-line .dashes {
            letter-spacing: 2px;
        }
        .tahun-ajaran-line .year-dots {
            display: inline-block;
            border-bottom: 1px dotted #333;
            width: 18mm;
            min-height: 1em;
            vertical-align: bottom;
            text-align: center;
        }

        /* ================= Watermark (teks melingkar via canvas sederhana) ================= */
        .watermark {
            position: absolute;
            top: 42%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120mm;
            height: 120mm;
            z-index: 0;
            pointer-events: none;
            opacity: 0.08;
        }
        .watermark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* ================= Body ================= */
        .body-content {
            position: relative;
            z-index: 1;
        }

        .field-row {
            margin-bottom: 3mm;
            font-size: 11pt;
        }
        .field-row table {
            width: 100%;
            border-collapse: collapse;
        }
        .field-row td {
            vertical-align: top;
            padding: 1mm 0;
        }
        .field-label {
            width: 55mm;
            font-weight: bold;
        }
        .field-colon {
            width: 4mm;
        }
        .field-value {
            border-bottom: 1px dotted #333;
        }

        .dengan-ini {
            text-align: center;
            margin: 6mm 0 4mm 0;
            font-size: 11pt;
        }

        .nama-siswa {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin: 0 auto 5mm auto;
            padding: 0 25mm;
            border-bottom: 1px solid #333;
            padding-bottom: 2mm;
            min-height: 9mm;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .lulus {
            text-align: center;
            font-size: 26pt;
            font-weight: bold;
            letter-spacing: 14px;
            margin: 4mm 0 2mm 0;
        }
        .dari {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 4mm;
        }

        .paragraph {
            margin: 4mm 0;
            font-size: 11pt;
            text-align: justify;
            line-height: 1.7;
        }
        .inline-dots {
            display: inline-block;
            border-bottom: 1px dotted #333;
            min-width: 50mm;
            min-height: 1em;
            vertical-align: bottom;
            padding: 0 2mm;
        }
        .inline-dots.short { min-width: 25mm; }
        .inline-dots.wide { min-width: 90mm; }

        /* ================= Footer: pas foto + ttd ================= */
        .footer {
            position: absolute;
            bottom: 15mm;
            left: 18mm;
            right: 18mm;
            display: table;
            width: calc(215mm - 36mm);
        }
        .footer-left, .footer-right {
            display: table-cell;
            vertical-align: top;
        }
        .footer-left { width: 45%; text-align: center; }
        .footer-right { width: 55%; text-align: left; padding-left: 10mm; }

        .pasfoto {
            display: inline-block;
            width: 32mm;
            height: 43mm;
            border: 1px dashed #555;
            text-align: center;
            font-size: 9pt;
            color: #555;
            padding-top: 10mm;
            line-height: 1.4;
        }

        .kepala {
            font-size: 11pt;
        }
        .kepala .space-ttd {
            height: 22mm;
        }
        .kepala .nama-kepala {
            border-bottom: 1px solid #333;
            display: inline-block;
            min-width: 70mm;
            font-weight: bold;
            padding-bottom: 1mm;
        }
        .kepala .nip {
            font-size: 10pt;
            margin-top: 1mm;
        }

        /* Dummy watermark (tetap ada supaya terlihat bukan dokumen resmi) */
        .dummy-mark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-28deg);
            font-size: 72pt;
            font-weight: 900;
            color: rgba(220, 38, 38, 0.08);
            letter-spacing: 14px;
            z-index: 2;
            pointer-events: none;
        }

        .dummy-badge {
            position: absolute;
            top: 8mm;
            left: 50%;
            transform: translateX(-50%);
            border: 1.5px solid #dc2626;
            color: #dc2626;
            padding: 2px 8px;
            font-size: 8pt;
            font-weight: bold;
            letter-spacing: 1.5px;
            z-index: 3;
            background: rgba(255, 255, 255, 0.85);
        }
    </style>
</head>
<body>
    @php
        $namaSekolah = $settings['nama_sekolah'] ?? ($settings['nama_madrasah'] ?? 'Nama Madrasah');
        $alamatSekolah = $settings['alamat_sekolah'] ?? ($settings['alamat_madrasah'] ?? '');
        $npsn = $settings['npsn'] ?? ($settings['nomor_pokok_sekolah_nasional'] ?? '');
        $kepalaSekolah = $settings['nama_kepala_sekolah'] ?? ($settings['kepala_madrasah'] ?? '');
        $nipKepala = $settings['nip_kepala_sekolah'] ?? ($settings['nip_kepala_madrasah'] ?? '');
        $nomenklatur = $settings['nomenklatur_kementerian'] ?? 'KEMENTERIAN AGAMA REPUBLIK INDONESIA';
        $urusan = $settings['urusan_pemerintahan'] ?? 'URUSAN PEMERINTAHAN DI BIDANG PENDIDIKAN';
        $kotaSurat = $settings['kota_surat'] ?? '';
        \Carbon\Carbon::setLocale('id');

        // Pecah tahun ajaran 2025/2026 → [2025, 2026]
        $tahunParts = explode('/', $tahunAjaran->nama_tahun_ajaran);
        $tahunKiri = $tahunParts[0] ?? '';
        $tahunKanan = $tahunParts[1] ?? '';
    @endphp

    @foreach ($siswas as $siswa)
        <div class="page">
            {{-- Dummy badge + watermark --}}
            <div class="dummy-badge">DOKUMEN DUMMY &middot; BUKAN IJAZAH RESMI</div>
            <div class="dummy-mark">DUMMY</div>

            {{-- Header: logo kiri + no ijazah kanan --}}
            <div class="header">
                <div class="header-left">
                    <div class="logo-kemenag">
                        {{-- Kalau ingin pakai logo asli, taruh file di public/img/logo-kemenag.png --}}
                        @if (file_exists(public_path('img/logo-kemenag.png')))
                            <img src="{{ public_path('img/logo-kemenag.png') }}" alt="Logo">
                        @else
                            <div style="width:14mm;height:14mm;border:1px solid #999;display:inline-block;text-align:center;line-height:14mm;font-size:8pt;color:#999;">LOGO</div>
                        @endif
                    </div>
                    <div class="nomenklatur-mini">
                        {{ $nomenklatur }}<br>
                        {{ $urusan }}
                    </div>
                </div>
                <div class="header-right">
                    <div class="nomor-ijazah">
                        No. Ijazah: <span class="dots"></span>
                    </div>
                </div>
            </div>

            {{-- Garuda + nomenklatur utama --}}
            <div class="garuda-wrap">
                @if (file_exists(public_path('img/garuda.png')))
                    <img src="{{ public_path('img/garuda.png') }}" alt="Garuda">
                @else
                    <div style="height:22mm;display:inline-block;text-align:center;line-height:22mm;font-size:9pt;color:#999;border:1px dashed #ccc;width:22mm;">GARUDA</div>
                @endif
            </div>
            <div class="nomenklatur-main">
                {{ $nomenklatur }}<br>
                {{ $urusan }}.
            </div>

            {{-- Judul --}}
            <div class="judul-ijazah">IJAZAH</div>
            <div class="tahun-ajaran-line">
                <span class="dashes">................</span>
                TAHUN AJARAN
                <span class="year-dots">{{ $tahunKiri }}</span>
                /
                <span class="year-dots">{{ $tahunKanan }}</span>
            </div>

            {{-- Watermark logo sekolah (opsional) --}}
            <div class="watermark">
                @if (file_exists(public_path('img/logo-sekolah.png')))
                    <img src="{{ public_path('img/logo-sekolah.png') }}" alt="">
                @endif
            </div>

            {{-- Body --}}
            <div class="body-content">
                <div class="field-row">
                    <table>
                        <tr>
                            <td class="field-label">Program Keahlian</td>
                            <td class="field-colon">:</td>
                            <td class="field-value">&nbsp;</td>
                        </tr>
                    </table>
                </div>
                <div class="field-row">
                    <table>
                        <tr>
                            <td class="field-label">Kompetensi Keahlian</td>
                            <td class="field-colon">:</td>
                            <td class="field-value">&nbsp;</td>
                        </tr>
                    </table>
                </div>

                <div class="dengan-ini">Dengan ini menyatakan bahwa,</div>

                <div class="nama-siswa">{{ $siswa->nama_lengkap }}</div>

                <div class="field-row">
                    <table>
                        <tr>
                            <td class="field-label">tempat, tanggal lahir</td>
                            <td class="field-colon">:</td>
                            <td class="field-value">
                                {{ $siswa->tempat_lahir ?: '' }}@if ($siswa->tanggal_lahir), {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') }}@endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="field-row">
                    <table>
                        <tr>
                            <td class="field-label">Nomor Induk Siswa Nasional</td>
                            <td class="field-colon">:</td>
                            <td class="field-value">{{ $siswa->nisn ?: '' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="lulus">L U L U S</div>
                <div class="dari">dari,</div>

                <div class="field-row">
                    <table>
                        <tr>
                            <td class="field-label">satuan pendidikan</td>
                            <td class="field-colon">:</td>
                            <td class="field-value">{{ $namaSekolah }}</td>
                        </tr>
                    </table>
                </div>
                <div class="field-row">
                    <table>
                        <tr>
                            <td class="field-label">Nomor Pokok Sekolah Nasional</td>
                            <td class="field-colon">:</td>
                            <td class="field-value">{{ $npsn ?: '' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="paragraph">
                    berdasarkan Keputusan Kepala <span class="inline-dots wide"></span><br>
                    Nomor <span class="inline-dots"></span> tanggal <span class="inline-dots short"></span> setelah memenuhi
                    seluruh kriteria sesuai dengan peraturan perundang-undangan.
                </div>
            </div>

            {{-- Footer: pas foto + kepala --}}
            <div class="footer">
                <div class="footer-left">
                    <div class="pasfoto">
                        pasfoto<br>3x4 cm<br>hitam putih<br>atau<br>berwarna
                    </div>
                </div>
                <div class="footer-right">
                    <div class="kepala">
                        {{ $kotaSurat ? $kotaSurat.', ' : '' }}{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                        Kepala,
                        <div class="space-ttd"></div>
                        <div class="nama-kepala">{{ $kepalaSekolah ?: '' }}</div>
                        <div class="nip">NIP {{ $nipKepala ?: '' }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</body>
</html>