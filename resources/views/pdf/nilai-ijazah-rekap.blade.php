<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekap Nilai Ijazah - {{ $tahunAjaran->nama_tahun_ajaran }}</title>
    <style>
        @page {
            size: 330mm 215mm;
            /* F4 Landscape */
            margin: 10mm 8mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #1a1a1a;
        }

        .header {
            text-align: center;
            margin-bottom: 5mm;
        }

        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 1mm;
        }

        .header h2 {
            font-size: 11pt;
            font-weight: normal;
            margin-bottom: 1mm;
        }

        .header .school-name {
            font-size: 12pt;
            font-weight: bold;
        }

        .header .tahun-ajaran {
            font-size: 10pt;
            margin-top: 1mm;
        }

        table.rekap {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }

        table.rekap th,
        table.rekap td {
            border: 0.5pt solid #333;
            padding: 2px 3px;
            text-align: center;
            vertical-align: middle;
        }

        table.rekap th {
            background-color: #e5e7eb;
            font-weight: bold;
            font-size: 7.5pt;
        }

        table.rekap td.nama {
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            max-width: 45mm;
        }

        table.rekap td.nisn {
            font-family: "Courier New", monospace;
            font-size: 7.5pt;
        }

        table.rekap td.nilai {
            font-size: 8pt;
        }

        table.rekap td.rata-raport {
            background-color: #dbeafe;
            font-weight: bold;
        }

        table.rekap td.rata-um {
            background-color: #ede9fe;
            font-weight: bold;
        }

        table.rekap td.rata-akhir {
            background-color: #dcfce7;
            font-weight: bold;
        }

        .footer-info {
            margin-top: 4mm;
            font-size: 8pt;
        }

        .footer-info p {
            margin-bottom: 1mm;
        }

        .ttd-section {
            margin-top: 8mm;
            width: 100%;
        }

        .ttd-section td {
            vertical-align: top;
            padding: 0;
        }

        .ttd-right {
            text-align: center;
            font-size: 10pt;
        }

        .ttd-right .nama-pejabat {
            font-weight: bold;
            border-bottom: 1px solid #333;
            display: inline-block;
            min-width: 55mm;
            padding-bottom: 1mm;
            margin-top: 18mm;
        }

        .ttd-right .nip {
            font-size: 9pt;
            margin-top: 1mm;
        }
    </style>
</head>

<body>
    @php
        $namaSekolah = $settings['nama_sekolah'] ?? 'Nama Madrasah';
        $kepalaSekolah = $settings['nama_kepala'] ?? '';
        $nipKepala = $settings['nip_kepala'] ?? '';
        $kota = $settings['kota'] ?? '';
        \Carbon\Carbon::setLocale('id');
    @endphp

    <div class="header">
        <h1>REKAP NILAI IJAZAH</h1>
        <div class="school-name">{{ $namaSekolah }}</div>
        <div class="tahun-ajaran">Tahun Ajaran {{ $tahunAjaran->nama_tahun_ajaran }}</div>
        <h2>Nilai Akhir = (Rata-rata Raport × 70%) + (Nilai UM × 30%)</h2>
    </div>

    <table class="rekap">
        <thead>
            <tr>
                <th style="width: 7mm;">No</th>
                <th style="width: 22mm;">NISN</th>
                <th style="min-width: 40mm;">Nama Siswa</th>
                @foreach ($mapels as $mapel)
                    <th title="{{ $mapel->nama_mapel }}">{{ $mapel->short_name }}</th>
                @endforeach
                <th style="background-color: #dbeafe;">Rata2<br>Raport</th>
                <th style="background-color: #ede9fe;">Rata2<br>UM</th>
                <th style="background-color: #dcfce7;">Rata2<br>Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswas as $idx => $siswa)
                @php
                    $sumRaport = 0.0;
                    $countRaport = 0;
                    $sumUm = 0.0;
                    $countUm = 0;
                    $sumFinal = 0.0;
                    $countFinal = 0;
                @endphp
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td class="nisn">{{ $siswa->nisn ?: '-' }}</td>
                    <td class="nama">{{ $siswa->nama_lengkap }}</td>
                    @foreach ($mapels as $mapel)
                        @php
                            $score = $scoresGrid[$siswa->id][$mapel->id] ?? null;

                            $rata = $score
                                ? $calculator->rataRataRaport([
                                    $score->kelas_4_semester_1,
                                    $score->kelas_4_semester_2,
                                    $score->kelas_5_semester_1,
                                    $score->kelas_5_semester_2,
                                    $score->kelas_6_semester_1,
                                ])
                                : null;

                            $um = $score && $score->nilai_um !== null ? (float) $score->nilai_um : null;
                            $final = $calculator->nilaiIjazah($rata, $um);

                            if ($rata !== null) {
                                $sumRaport += $rata;
                                $countRaport++;
                            }
                            if ($um !== null) {
                                $sumUm += $um;
                                $countUm++;
                            }
                            if ($final !== null) {
                                $sumFinal += $final;
                                $countFinal++;
                            }
                        @endphp
                        <td class="nilai">
                            {{ $final !== null ? number_format($final, 2) : '-' }}
                        </td>
                    @endforeach
                    <td class="rata-raport">
                        {{ $countRaport > 0 ? number_format($sumRaport / $countRaport, 2) : '-' }}
                    </td>
                    <td class="rata-um">
                        {{ $countUm > 0 ? number_format($sumUm / $countUm, 2) : '-' }}
                    </td>
                    <td class="rata-akhir">
                        {{ $countFinal > 0 ? number_format($sumFinal / $countFinal, 2) : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-info">
        <p><strong>Keterangan:</strong></p>
        <p>• Nilai tiap mapel = (Rata-rata Raport Kelas 4–6 × 70%) + (Nilai UM × 30%)</p>
        <p>• Rata-rata Raport = rata-rata dari rata-rata raport tiap mapel</p>
        <p>• Rata-rata UM = rata-rata nilai UM tiap mapel</p>
        <p>• Rata-rata Akhir = rata-rata dari nilai akhir tiap mapel</p>
    </div>

    <table class="ttd-section" style="border: none;">
        <tr>
            <td style="width: 60%; border: none;"></td>
            <td style="width: 40%; border: none;">
                <div class="ttd-right">
                    <div>{{ $kota ? $kota . ', ' : '' }}{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                    <div>Kepala Madrasah,</div>
                    <div class="nama-pejabat">{{ $kepalaSekolah }}</div>
                    <div class="nip">NIP. {{ $nipKepala }}</div>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
