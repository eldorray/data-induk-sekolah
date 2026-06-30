# Modul Surat Universal — Design

## Tujuan
Satu modul surat serbaguna untuk membuat surat apa saja (keterangan, tugas, izin, dll)
tanpa perlu bikin modul baru tiap jenis surat. Mengikuti pola surat existing
(Livewire management + controller dompdf + blade PDF).

## Data — tabel `surat_universals`
| field | tipe | keterangan |
|---|---|---|
| `id` | bigint | |
| `jenis` | string | label bebas, mis. "Surat Keterangan", "Surat Tugas" |
| `judul` | string | judul tampil di PDF (huruf besar, garis bawah) |
| `nomor_surat` | string | auto-generate `urut/KODE/SU/bulan-romawi/tahun`, bisa diedit |
| `tanggal_surat` | date | |
| `jenjang` | string | dropdown: MI, SMP, RA/TK, MTs, MA, Lainnya |
| `kop_path` | string nullable | gambar kop diupload tiap surat (disk `public`, folder `kop-surat`) |
| `isi` | longtext | HTML dari Trix editor |
| `tempat` | string nullable | tempat surat, default dari `SchoolSetting` (`kuitansi_kabupaten`) |
| `ttd_jabatan` | string nullable | blok tanda tangan (default "Kepala Madrasah") |
| `ttd_nama` | string nullable | default dari `kuitansi_kepala_madrasah` |
| `ttd_nip` | string nullable | |
| timestamps | | |

## Komponen
- `app/Models/SuratUniversal.php` — fillable + cast `tanggal_surat`, static `generateNomorSurat()`.
- `app/Livewire/SuratUniversalManagement.php` — list + modal CRUD (pola `SuratKeteranganAktifManagement`).
  Upload kop via `WithFileUploads`. Isi via Trix.
- `resources/views/livewire/surat-universal-management.blade.php` — Trix dimuat lewat
  Livewire `@assets` (CSS+JS CDN, sekali) + `@script` (wiring). Kop preview + validasi image.
- `app/Http/Controllers/SuratUniversalController.php` — `printPdf()` dompdf stream, kertas F4.
- `resources/views/pdf/surat-universal.blade.php` — kop (img `public_path('storage/...')`) →
  garis → judul + nomor → isi (HTML Trix, `{!! !!}`) → blok ttd (+ stempel/ttd kepala dari settings).
- Route `surat-universal` (index, Livewire) + `surat-universal/{id}/print`, middleware `auth, role:admin`.
- Link sidebar di `layouts/admin.blade.php` grup "Surat".

## Editor
Trix (open source, Basecamp). Dimuat via CDN tanpa build step. Output HTML bersih →
langsung dirender dompdf. Attachment/upload gambar di dalam isi dimatikan (kop terpisah).

## Skip sengaja (YAGNI)
- Kolom `status`/approval flow — universal tak butuh; print selalu boleh. Tambah kalau perlu.
- Kop tersimpan/reuse — diputuskan upload tiap surat.
