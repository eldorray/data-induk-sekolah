<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Surat mutasi adalah dokumen historis, tetapi mutasi_siswas tidak punya
     * foreign key sehingga siswanya bisa hilang dan surat jadi tidak bisa
     * dicetak. Simpan identitas siswa apa adanya saat surat dibuat.
     */
    public function up(): void
    {
        Schema::table('mutasi_siswas', function (Blueprint $table) {
            $table->json('siswa_snapshot')->nullable()->after('siswa_id');
        });

        $models = [
            'siswa_mi' => \App\Models\SiswaMi::class,
            'siswa_smp' => \App\Models\SiswaSmp::class,
            'AppModelsSiswaMi' => \App\Models\SiswaMi::class,
            'AppModelsSiswaSmp' => \App\Models\SiswaSmp::class,
        ];

        foreach (DB::table('mutasi_siswas')->whereNull('siswa_snapshot')->get() as $row) {
            $model = $models[$row->siswa_type] ?? null;
            $siswa = $model ? $model::find($row->siswa_id) : null;

            if ($siswa === null) {
                continue;
            }

            DB::table('mutasi_siswas')->where('id', $row->id)->update([
                'siswa_snapshot' => json_encode([
                    'nama_lengkap' => $siswa->nama_lengkap,
                    'nisn' => $siswa->nisn,
                    'jenis_kelamin' => $siswa->jenis_kelamin,
                    'tempat_lahir' => $siswa->tempat_lahir,
                    'tanggal_lahir' => $siswa->tanggal_lahir?->format('Y-m-d'),
                    'tingkat_rombel' => $siswa->tingkat_rombel,
                ]),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('mutasi_siswas', function (Blueprint $table) {
            $table->dropColumn('siswa_snapshot');
        });
    }
};
