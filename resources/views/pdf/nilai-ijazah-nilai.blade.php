<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nilai Ijazah - {{ $tahunAjaran->nama_tahun_ajaran }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 12mm 15mm 12mm;
        }

        * { box-sizing: border-box; }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10.5pt;
            color: #111;
            margin: 0;
            padding: 0;
        }

        .page {
            page-break-after: always;
        }

        .page:last-child { page-break-after: auto; }

        .header {
            text-align: center;
            border-bottom: 2px solid #111;
            padding-bottom: 6mm;
            margin-bottom: 6mm;
        }

        .header .line1 {
            font-size: 11pt;
            font-weight: bold;
        }
        .header .line2 {
            font-size: 15pt;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 2mm 0 1mm 0;
        }
        .header .line3 {
            font-size: 9pt;
            color: #374151;
        }

        .section-title {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            text-decoration: underline;
            margin: 5mm 0 6mm 0;
            letter-spacing: 1px;
        }

        .identity {
            width: 100%;
            margin-bottom: 5mm;
        }

        .identity table {
            width: 100%;
            border-collapse: collapse;
        }

        .identity td {
            padding: 1mm 0;
            font-size: 10.5pt;
        }

        .identity td.lab { width: 40mm; }
        .identity td.col { width: 4mm; }

        table.nilai {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2mm;
        }

        table.nilai th,
        table.nilai td {
            border: 1px solid #111;
            padding: 1.5mm 2mm;
            font-size: 9.5pt;
        }

        table.nilai th {
            background: #e5e7eb;
            text-align: center;
            font-weight: bold;
        }

        table.nilai td.num { text-align: center; }
        table.nilai td.mapel { text-align: left; }
        table.nilai td.final { text-align: center; font-weight: bold; background: #f3f4f6; }
        table.nilai td.empty { text-align: center; font-style: italic; color: #b91c1c; }

        .summary {
            margin-top: 4mm;
            font-size: 10pt;
        }

        .summary table {
            width: 80mm;
            margin-left: auto;
            border-collapse: collapse;
        }

        .summary td {
            padding: 1.5mm 2mm;
            border: 1px solid #111;
        }

        .summary td.final {
            font-weight: bold;
            background: #f3f4f6;
            text-align: right;
        }

        .warning {
            margin-top: 4mm;
            padding: 3mm 4mm;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            font-size: 9pt;
        }

        .signature {
            margin-top: 12mm;
            width: 100%;
        }

        .signature table {
            width: 100%;
        }

        .signature td {
            vertical-align: top;
            font-size: 10.5pt;
        }

        .sig-right {
            text-align: center;
            width: 70mm;
            margin-left: auto;
        }

        .sig-space {
            height: 20mm;
        }

        .sig-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .footnote {
            margin-top: 6mm;
            font-size: 8.5pt;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
    @php
        $namaSekolah = $settings['nama_sekolah'] ?? ($settings['nama_madrasah'] ?? 'Nama Madrasah');
        $alamatSekolah = $settings['alamat_sekolah'] ?? ($settings['alamat_madrasah'] ?? '');
        $kepalaSekolah = $settings['nama_kepala_sekolah'] ?? ($settings['kepala_madrasah'] ?? '');
        $nipKepala = $settings['nip_kepala_sekolah'] ?? ($settings['nip_kepala_madrasah'] ?? '');
        $tempatTtd = $settings['kota_surat'] ?? ($settings['kabupaten_sekolah'] ?? '');
        \Carbon\Carbon::setLocale('id');
        $tanggalCetak = \Carbon\Carbon::now()->translatedFormat('d F Y');
    @endphp

    @foreach ($siswas as $siswa)
        @php
            $scoresSiswa = $scoresGrouped->get($siswa->id, collect())->keyBy('mapel_id');
            $anyIncomplete = false;
            $totalNilaiIjazah = 0;
            $countLengkap = 0;
        @endphp

        <div class="page">
            <div class="header">
                <div class="line1">PEMERINTAH KEMENTERIAN AGAMA</div>
                <div class="line2">{{ strtoupper($namaSekolah) }}</div>
                @if ($alamatSekolah)
                    <div class="line3">{{ $alamatSekolah }}</div>
                @endif
            </div>

            <div class="section-title">DAFTAR NILAI IJAZAH</div>

            <div class="identity">
                <table>
                    <tr>
                        <td class="lab">Nama</td>
                        <td class="col">:</td>
                        <td><strong>{{ $siswa->nama_lengkap }}</strong></td>
                        <td class="lab">Tahun Ajaran</td>
                        <td class="col">:</td>
                        <td><strong>{{ $tahunAjaran->nama_tahun_ajaran }}</strong></td>
                    </tr>
                    <tr>
                        <td class="lab">NISN</td>
                        <td class="col">:</td>
                        <td>{{ $siswa->nisn ?: '-' }}</td>
                        <td class="lab">Tempat, Tgl Lahir</td>
                        <td class="col">:</td>
                        <td>{{ $siswa->tempat_lahir ?: '-' }}@if ($siswa->tanggal_lahir), {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') }}@endif</td>
                    </tr>
                    <tr>
                        <td class="lab">Kelas</td>
                        <td class="col">:</td>
                        <td>{{ $siswa->tingkat_rombel ?: 'VI' }}</td>
                        <td class="lab">Tanggal Cetak</td>
                        <td class="col">:</td>
                        <td>{{ $tanggalCetak }}</td>
                    </tr>
                </table>
            </div>

            <table class="nilai">
                <thead>
                    <tr>
                        <th rowspan="2" style="width:8mm;">No</th>
                        <th rowspan="2">Mata Pelajaran</th>
                        <th colspan="5">Nilai Raport</th>
                        <th rowspan="2" style="width:16mm;">Rata-<br>rata</th>
                        <th rowspan="2" style="width:14mm;">UM</th>
                        <th rowspan="2" style="width:18mm;">Nilai<br>Ijazah</th>
                    </tr>
                    <tr>
                        <th style="width:11mm;">K4 S1</th>
                        <th style="width:11mm;">K4 S2</th>
                        <th style="width:11mm;">K5 S1</th>
                        <th style="width:11mm;">K5 S2</th>
                        <th style="width:11mm;">K6 S1</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mapels as $idx => $mapel)
                        @php
                            $score = $scoresSiswa->get($mapel->id);
                            $k41 = $score?->kelas_4_semester_1;
                            $k42 = $score?->kelas_4_semester_2;
                            $k51 = $score?->kelas_5_semester_1;
                            $k52 = $score?->kelas_5_semester_2;
                            $k61 = $score?->kelas_6_semester_1;
                            $um = $score?->nilai_um;

                            $rata = $calculator->rataRataRaport([$k41, $k42, $k51, $k52, $k61]);
                            $final = $calculator->nilaiIjazah($rata, $um !== null ? (float) $um : null);

                            if ($final === null) {
                                $anyIncomplete = true;
                            } else {
                                $totalNilaiIjazah += $final;
                                $countLengkap++;
                            }
                        @endphp
                        <tr>
                            <td class="num">{{ $idx + 1 }}</td>
                            <td class="mapel">{{ $mapel->nama_mapel }}</td>
                            <td class="num">{{ $k41 !== null ? number_format((float) $k41, 2) : '-' }}</td>
                            <td class="num">{{ $k42 !== null ? number_format((float) $k42, 2) : '-' }}</td>
                            <td class="num">{{ $k51 !== null ? number_format((float) $k51, 2) : '-' }}</td>
                            <td class="num">{{ $k52 !== null ? number_format((float) $k52, 2) : '-' }}</td>
                            <td class="num">{{ $k61 !== null ? number_format((float) $k61, 2) : '-' }}</td>
                            <td class="num">{{ $rata !== null ? number_format($rata, 2) : '-' }}</td>
                            <td class="num">{{ $um !== null ? number_format((float) $um, 2) : '-' }}</td>
                            @if ($final === null)
                                <td class="empty">Belum Lengkap</td>
                            @else
                                <td class="final">{{ number_format($final, 2) }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="summary">
                <table>
                    <tr>
                        <td>Jumlah Mapel</td>
                        <td class="final">{{ $mapels->count() }}</td>
                    </tr>
                    <tr>
                        <td>Mapel Lengkap</td>
                        <td class="final">{{ $countLengkap }}</td>
                    </tr>
                    <tr>
                        <td>Rata-rata Nilai Ijazah</td>
                        <td class="final">
                            {{ $countLengkap > 0 ? number_format($totalNilaiIjazah / $countLengkap, 2) : '-' }}
                        </td>
                    </tr>
                </table>
            </div>

            @if ($anyIncomplete)
                <div class="warning">
                    <strong>Perhatian:</strong> Terdapat mata pelajaran dengan nilai belum lengkap
                    (kolom raport K4-K6 atau nilai UM masih kosong). Nilai ijazah pada baris tersebut
                    belum bisa dihitung.
                </div>
            @endif

            <div class="signature">
                <table>
                    <tr>
                        <td></td>
                        <td>
                            <div class="sig-right">
                                {{ $tempatTtd ? $tempatTtd . ', ' : '' }}{{ $tanggalCetak }}<br>
                                Kepala Madrasah,
                                <div class="sig-space"></div>
                                <div class="sig-name">{{ $kepalaSekolah ?: '( ______________________ )' }}</div>
                                @if ($nipKepala)
                                    <div>NIP. {{ $nipKepala }}</div>
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="footnote">
                Rumus: Nilai Ijazah = (Rata-rata Raport K4-K6 &times; 70%) + (Nilai UM &times; 30%).
            </div>
        </div>
    @endforeach
</body>
</html>