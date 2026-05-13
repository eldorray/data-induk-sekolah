<?php

namespace App\Exports;

use App\Models\MapelMi;
use App\Models\NilaiIjazahScore;
use App\Models\NilaiIjazahTahunAjaran;
use App\Models\SiswaMi;
use App\Services\NilaiIjazahCalculator;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Export rekap nilai rata-rata ijazah (70% raport + 30% UM) ke Excel.
 *
 * Kolom:
 *   No | NISN | Nama Siswa | Kelas | [nilai akhir per mapel...] | Rata-rata Raport | Rata-rata UM | Rata-rata Akhir
 */
class NilaiIjazahRekapExport implements FromArray, WithHeadings, WithStyles, WithEvents, WithTitle, ShouldAutoSize
{
    /** @var \Illuminate\Support\Collection<int, \App\Models\MapelMi> */
    protected $mapels;

    /** @var \Illuminate\Support\Collection<int, \App\Models\SiswaMi> */
    protected $siswas;

    protected array $scoresGrid = [];

    protected NilaiIjazahCalculator $calculator;

    public function __construct(
        protected NilaiIjazahTahunAjaran $tahunAjaran,
    ) {
        $this->calculator = app(NilaiIjazahCalculator::class);

        $this->mapels = MapelMi::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('nama_mapel')
            ->get();

        $this->siswas = SiswaMi::query()
            ->where('status', 'Aktif')
            ->where(function ($q) {
                $q->where('tingkat_rombel', 'like', '%6%')
                    ->orWhere('tingkat_rombel', 'like', '%VI%');
            })
            ->orderBy('nama_lengkap')
            ->get();

        // Build grid $scoresGrid[siswa_id][mapel_id] = score
        $scores = NilaiIjazahScore::query()
            ->where('nilai_ijazah_tahun_ajaran_id', $tahunAjaran->id)
            ->get();

        foreach ($scores as $score) {
            $this->scoresGrid[$score->siswa_id][$score->mapel_id] = $score;
        }
    }

    public function title(): string
    {
        return 'Rekap Nilai Ijazah '.$this->tahunAjaran->nama_tahun_ajaran;
    }

    public function headings(): array
    {
        $headers = ['No', 'NISN', 'Nama Siswa', 'Kelas'];

        foreach ($this->mapels as $mapel) {
            $headers[] = $mapel->nama_mapel;
        }

        $headers[] = 'Rata-rata Raport';
        $headers[] = 'Rata-rata UM';
        $headers[] = 'Rata-rata Akhir (Ijazah)';

        return $headers;
    }

    public function array(): array
    {
        $rows = [];
        $no = 1;

        foreach ($this->siswas as $siswa) {
            $row = [
                $no++,
                $siswa->nisn ?: '-',
                $siswa->nama_lengkap,
                $siswa->tingkat_rombel ?: '-',
            ];

            $sumRaport = 0.0;
            $countRaport = 0;
            $sumUm = 0.0;
            $countUm = 0;
            $sumFinal = 0.0;
            $countFinal = 0;

            foreach ($this->mapels as $mapel) {
                /** @var \App\Models\NilaiIjazahScore|null $score */
                $score = $this->scoresGrid[$siswa->id][$mapel->id] ?? null;

                $rata = $score
                    ? $this->calculator->rataRataRaport([
                        $score->kelas_4_semester_1,
                        $score->kelas_4_semester_2,
                        $score->kelas_5_semester_1,
                        $score->kelas_5_semester_2,
                        $score->kelas_6_semester_1,
                    ])
                    : null;

                $um = $score && $score->nilai_um !== null ? (float) $score->nilai_um : null;
                $final = $this->calculator->nilaiIjazah($rata, $um);

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

                $row[] = $final !== null ? round($final, 2) : '-';
            }

            $row[] = $countRaport > 0 ? round($sumRaport / $countRaport, 2) : '-';
            $row[] = $countUm > 0 ? round($sumUm / $countUm, 2) : '-';
            $row[] = $countFinal > 0 ? round($sumFinal / $countFinal, 2) : '-';

            $rows[] = $row;
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        // Header bold + bg abu-abu
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '374151'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $mapelCount = $this->mapels->count();
                $siswaCount = $this->siswas->count();

                $lastRow = $siswaCount + 1; // +1 untuk header
                $lastColumnIndex = 4 + $mapelCount + 3; // No, NISN, Nama, Kelas + mapel + 3 ringkasan
                $lastColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastColumnIndex);

                // Border seluruh tabel
                $range = 'A1:'.$lastColumnLetter.$lastRow;
                $sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D1D5DB'],
                        ],
                    ],
                ]);

                // Row height header
                $sheet->getRowDimension(1)->setRowHeight(28);

                // Kolom angka di tengah
                if ($siswaCount > 0) {
                    // No = kolom A, mapel score dst. mulai kolom E (index 5)
                    $startMapelCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5);
                    $endMapelCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(4 + $mapelCount);

                    $sheet->getStyle('A2:A'.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle($startMapelCol.'2:'.$endMapelCol.$lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Kolom ringkasan
                    $sumColStart = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(4 + $mapelCount + 1);
                    $sumColEnd = $lastColumnLetter;
                    $sheet->getStyle($sumColStart.'2:'.$sumColEnd.$lastRow)
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    // Highlight 3 kolom ringkasan dengan warna
                    $summaryColors = ['DBEAFE', 'EDE9FE', 'DCFCE7']; // biru, ungu, hijau
                    for ($i = 0; $i < 3; $i++) {
                        $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(4 + $mapelCount + 1 + $i);
                        $sheet->getStyle($col.'2:'.$col.$lastRow)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => $summaryColors[$i]],
                            ],
                            'font' => ['bold' => true],
                        ]);
                    }
                }

                // Freeze baris 1 dan kolom A-D (agar identitas siswa & header selalu terlihat)
                $sheet->freezePane('E2');
            },
        ];
    }
}