# LPJ BOS — Cetak PDF Gabungan (Design Spec)

**Tanggal:** 2026-06-13
**Status:** Disetujui

## Tujuan

Saat menekan **Cetak** pada sebuah LPJ BOS, hasilkan **satu file PDF** yang berisi seluruh dokumen LPJ secara berurutan:

1. **Kuitansi BOS** resmi (halaman pertama).
2. Lampiran **Foto**.
3. Lampiran **Kwitansi** (bukti bayar / nota / invoice).
4. Lampiran **Undangan**.

Lampiran gambar (JPG/PNG) dirender penuh sebagai halaman. Lampiran berbentuk **PDF** (mis. nota/invoice/undangan hasil scan) digabung **seluruh halamannya secara utuh**, bukan sekadar didaftar namanya.

## Keputusan yang Sudah Diambil

- **Mesin gabung PDF:** Ghostscript (`gs`). Pengguna memasangnya sekali: `brew install ghostscript`. Dipilih karena andal menggabung/merender semua versi PDF (termasuk dari scanner/HP) — tidak seperti pendekatan pure-PHP (FPDI) yang gagal pada PDF terkompresi modern.
- **Urutan halaman:** Kuitansi → Foto → Kwitansi → Undangan. Di dalam tiap kategori mengikuti **urutan manual** (`sort_order`) yang sudah diatur di halaman detail.
- **Rekap PDF tidak berubah** — hanya cetak per-LPJ (`lpj-bos.print`) yang diubah.

## Arsitektur

### Komponen baru

- **`App\Services\LpjBosPdfMerger`** — orkestrasi: menyusun daftar bagian terurut, merender tiap bagian menjadi PDF sementara, lalu menggabung via `PdfCombiner`. Mengembalikan konten PDF gabungan (string biner). Bertanggung jawab membersihkan file sementara.
- **`App\Services\PdfCombiner`** — pembungkus tipis pemanggilan Ghostscript. Method `combine(array $pdfPaths): string` menjalankan `gs` untuk menggabung daftar file PDF (sesuai urutan) dan mengembalikan biner hasil. Mengisolasi shell-call agar mudah di-mock dan agar deteksi/lokasi binari `gs` terpusat. Dibind ke container sehingga test dapat menggantinya dengan fake.

### Komponen yang dimodifikasi

- **`App\Http\Controllers\LpjBosController::printPdf`** — ganti implementasi: panggil `LpjBosPdfMerger`, stream hasil sebagai `lpj-bos-<nomor_bukti>.pdf` (inline).

### Template baru

- **`resources/views/pdf/lpj-bos-attachment.blade.php`** — merender **satu** lampiran gambar penuh 1 halaman A4: header (kategori + nama file + keterangan opsional) lalu gambar dari path absolut `storage_path('app/public/...')`.

### Template yang dihapus/digantikan

- **`resources/views/pdf/lpj-bos.blade.php`** — template lama (mendaftar nama PDF + render gambar) tidak lagi dipakai oleh alur cetak baru. Dihapus.

## Alur Data

`LpjBosController::printPdf($id)`
→ `LpjBosMerger->merge(LpjBos $lpj)`:

1. **Susun bagian terurut** (`buildParts`):
   - Bagian Kuitansi: tipe `kuitansi`.
   - Untuk kategori `[foto, kwitansi, undangan]` (urutan tetap ini), ambil lampiran kategori tsb urut `sort_order`; tiap lampiran jadi bagian bertipe `image` atau `pdf` (berdasarkan `is_pdf`/`is_image`).
2. **Render tiap bagian → path PDF sementara** (`storage/app/lpj-bos-merge-tmp/` atau `sys_get_temp_dir()`):
   - `kuitansi` → `Pdf::loadView('pdf.kuitansi', ['kuitansis' => collect([$lpj->kuitansi]), 'settings' => SchoolSetting::getAll()])->setPaper([0,0,612,936],'portrait')` → simpan ke temp.
   - `image` → `Pdf::loadView('pdf.lpj-bos-attachment', ['attachment' => $att])->setPaper('a4','portrait')` → simpan ke temp.
   - `pdf` → pakai file asli `storage_path('app/public/'.$att->file_path)` langsung (tanpa render ulang). Jika file tidak ada di disk → bagian dilewati.
3. **Gabung**: `PdfCombiner->combine($orderedPaths)` → biner PDF.
4. **Cleanup** file temp (juga bila terjadi error — gunakan `finally`).
5. Kembalikan biner; controller stream-kan.

## Ghostscript

- Perintah: `gs -dBATCH -dNOPAUSE -dQUIET -dSAFER -sDEVICE=pdfwrite -sOutputFile=<out> <in1> <in2> ...`.
- **Lokasi binari** ditentukan `PdfCombiner` berurutan: env `LPJ_BOS_GS_BINARY` → `/opt/homebrew/bin/gs` → `/usr/local/bin/gs` → `gs` (PATH).
- **Jika `gs` tidak ditemukan / gagal eksekusi** → lempar exception yang menghasilkan pesan jelas bagi pengguna admin: *"Ghostscript belum terpasang atau gagal dijalankan. Jalankan: brew install ghostscript"*. (Controller membiarkan exception ini naik → halaman error standar; pesan ringkas dapat ditampilkan.)

## Penanganan Error

- **`gs` tidak ada / gagal:** pesan instruksi pemasangan (lihat di atas).
- **File lampiran hilang di disk:** lewati bagian itu, lanjut menggabung sisanya.
- **LPJ tanpa lampiran sama sekali:** hasil PDF tetap berisi halaman Kuitansi saja (valid).
- **File temp:** selalu dibersihkan via `finally`.

## Testing

- **`tests/Unit/LpjBosPdfMergerTest.php`** — uji `buildParts()` (atau ekuivalen): urutan bagian benar (kuitansi dulu, lalu foto→kwitansi→undangan sesuai `sort_order`), dan tiap lampiran terklasifikasi `image`/`pdf` dengan benar. Tidak memanggil `gs` (mock/stub `PdfCombiner`).
- **`tests/Feature/LpjBosPdfTest.php`** (perbarui) — bind fake `PdfCombiner` di container yang mengembalikan biner dummy; assert route `lpj-bos.print` mengembalikan 200, header PDF, dan bahwa `combine` dipanggil dengan jumlah/urutan path yang sesuai (kuitansi + lampiran). Test tidak butuh `gs` terpasang.
- Test rekap PDF (`printRekap`) tetap seperti sebelumnya (tidak berubah).

## Di Luar Lingkup (YAGNI)

- Tidak mengubah rekap PDF.
- Tidak menambah opsi urutan/format yang dapat dikonfigurasi pengguna.
- Tidak melakukan kompresi/optimasi ukuran PDF gabungan di luar default `gs`.
- Tidak menyediakan fallback pure-PHP (FPDI) — Ghostscript sudah dipilih sebagai syarat.
