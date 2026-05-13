<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapelMi extends Model
{
    protected $table = 'mapel_mis';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'jam_per_minggu',
        'kelompok',
        'jurusan',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'jam_per_minggu' => 'integer',
    ];

    /**
     * Scope for active subjects
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for inactive subjects
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Aktif' : 'Tidak Aktif';
    }

    /**
     * Singkatan nama mapel untuk header tabel yang sempit.
     *
     * Strategi:
     *  1. Lookup pemetaan manual untuk nama mapel standar MI/Kemenag.
     *     (QH, AA, FQH, SKI, MTK, BIN, dll).
     *  2. Mapel 2+ kata → inisial tiap kata (skip penghubung), maks 5 huruf.
     *  3. Mapel 1 kata → huruf pertama + konsonan berikutnya (buang vokal),
     *     maks 3 huruf. Contoh: "Biologi" → BLG, "Sejarah" → SJR.
     *  4. Kalau semuanya gagal, baru pakai `kode_mapel` asalkan bukan pola
     *     generic MP### atau serupa. Fallback terakhir: 3 huruf pertama.
     */
    public function getShortNameAttribute(): string
    {
        $nama = trim((string) $this->nama_mapel);

        if ($nama === '' && ! empty($this->kode_mapel)) {
            return strtoupper($this->kode_mapel);
        }

        if ($nama === '') {
            return '-';
        }

        $lower = mb_strtolower($nama);

        // Pemetaan manual untuk nama-nama standar MI/Kemenag.
        // Gunakan gaya singkatan pendek konsonan-only seperti QH, AA, FQH, SKI, MTK.
        $map = [
            // PAI & rumpun agama
            'al-qur\'an hadits' => 'QH',
            'al-qur\'an hadis' => 'QH',
            'al-quran hadits' => 'QH',
            'al-quran hadis' => 'QH',
            'al quran hadits' => 'QH',
            'al quran hadis' => 'QH',
            'quran hadits' => 'QH',
            'quran hadis' => 'QH',
            'qur\'an hadits' => 'QH',
            'qur\'an hadis' => 'QH',
            'akidah akhlak' => 'AA',
            'aqidah akhlak' => 'AA',
            'fiqih' => 'FQH',
            'fikih' => 'FKH',
            'sejarah kebudayaan islam' => 'SKI',
            'ski' => 'SKI',
            'pendidikan agama islam' => 'PAI',
            'pai' => 'PAI',
            'pendidikan agama islam dan budi pekerti' => 'PAIBP',

            // Bahasa
            'bahasa indonesia' => 'BIN',
            'bahasa inggris' => 'BIG',
            'bahasa arab' => 'BAR',
            'bahasa jawa' => 'BJW',
            'bahasa sunda' => 'BSD',
            'bahasa daerah' => 'BDR',

            // Umum
            'matematika' => 'MTK',
            'ilmu pengetahuan alam' => 'IPA',
            'ilmu pengetahuan alam dan sosial' => 'IPAS',
            'ilmu pengetahuan sosial' => 'IPS',
            'ipa' => 'IPA',
            'ipas' => 'IPAS',
            'ips' => 'IPS',
            'pendidikan kewarganegaraan' => 'PKN',
            'pkn' => 'PKN',
            'pendidikan pancasila dan kewarganegaraan' => 'PPKN',
            'ppkn' => 'PPKN',
            'pendidikan pancasila' => 'PPC',
            'pancasila' => 'PCS',
            'pendidikan jasmani olahraga dan kesehatan' => 'PJOK',
            'pjok' => 'PJOK',
            'seni budaya dan prakarya' => 'SBDP',
            'sbdp' => 'SBDP',
            'tematik' => 'TMT',
            'tik' => 'TIK',
            'teknologi informasi dan komunikasi' => 'TIK',
            'informatika' => 'INF',
            'muatan lokal' => 'MLK',
            'mulok' => 'MLK',
        ];

        if (isset($map[$lower])) {
            return $map[$lower];
        }

        $words = preg_split('/\s+/', $nama) ?: [];
        $words = array_values(array_filter($words, fn ($w) => $w !== ''));

        // Multi-kata → inisial tiap kata (skip penghubung).
        if (count($words) >= 2) {
            $stopwords = ['dan', 'atau', 'dari', 'ke', 'di', 'yang', '&', '-'];
            $initial = '';
            foreach ($words as $w) {
                if (in_array(mb_strtolower($w), $stopwords, true)) {
                    continue;
                }
                $initial .= mb_strtoupper(mb_substr($w, 0, 1));
            }
            $initial = substr($initial, 0, 5);
            if ($initial !== '') {
                return $initial;
            }
        }

        // Satu kata → huruf pertama + konsonan berikutnya (buang vokal),
        // batasi 3 huruf. "Fiqih" → FQH, "Fikih" → FKH, "Sejarah" → SJR.
        $single = $words[0] ?? $nama;
        $upper = mb_strtoupper($single);
        $chars = preg_split('//u', $upper, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $result = '';
        $first = true;
        $vowels = ['A', 'I', 'U', 'E', 'O'];
        foreach ($chars as $c) {
            if (! preg_match('/[A-Z]/u', $c)) {
                continue;
            }
            if ($first) {
                $result .= $c;
                $first = false;

                continue;
            }
            if (! in_array($c, $vowels, true)) {
                $result .= $c;
            }
            if (mb_strlen($result) >= 3) {
                break;
            }
        }

        if ($result !== '') {
            return $result;
        }

        // Pakai kode_mapel hanya kalau bentuknya bukan pola generic (MP001, 001, dll).
        if (! empty($this->kode_mapel)) {
            $kode = strtoupper(trim($this->kode_mapel));
            $isGeneric = (bool) preg_match('/^(MP|MAPEL|KD|KODE)?\-?\s*\d+$/i', $kode);
            if (! $isGeneric) {
                return $kode;
            }
        }

        // Fallback terakhir: 3 huruf pertama (uppercase).
        return mb_strtoupper(mb_substr($nama, 0, 3));
    }
}
