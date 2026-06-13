# Desain Fitur LPJ BOS

Tanggal: 2026-06-13
Status: Disetujui untuk tahap perencanaan implementasi

## Ringkasan

Fitur **LPJ BOS** menjadi arsip dan generator laporan pertanggungjawaban untuk setiap **Kuitansi BOS**. Setiap satu Kuitansi BOS dapat memiliki maksimal satu LPJ BOS. LPJ tersebut menyimpan data kegiatan ringan dan banyak lampiran pendukung berupa foto, kwitansi scan, dan surat undangan.

Lampiran bersifat fleksibel: semua kategori bisa banyak dan boleh kosong saat awal dibuat. Status kelengkapan dihitung otomatis berdasarkan keberadaan minimal satu foto dan minimal satu kwitansi. Surat undangan bersifat opsional dan tidak mempengaruhi status kelengkapan.

## Tujuan

- Menghubungkan LPJ BOS langsung ke data Kuitansi BOS yang sudah ada.
- Memudahkan admin mengarsipkan foto, kwitansi scan, dan surat undangan untuk setiap kuitansi.
- Menyediakan cetak PDF per LPJ dan PDF rekap banyak LPJ berdasarkan filter.
- Menjaga ukuran file dengan kompres otomatis untuk gambar upload dan output PDF yang ringan.
- Mengikuti pola UI, Livewire, route, storage, dan PDF yang sudah digunakan project.

## Bukan Tujuan Fase Ini

- Satu LPJ berisi banyak Kuitansi BOS.
- Menggabungkan file PDF upload ke PDF final.
- Mengonversi halaman PDF upload menjadi gambar.
- Menambahkan dependency server seperti Ghostscript atau ImageMagick untuk merge/convert PDF.
- Membuat status manual seperti Draft, Final, atau Arsip.

## Struktur Menu

Sidebar admin akan memiliki grup menu baru:

```text
BOS
├── Kuitansi BOS
└── LPJ BOS
```

Menu **Kuitansi BOS** yang saat ini berdiri sendiri dipindahkan ke dalam grup **BOS**. Menu **LPJ BOS** ditambahkan di bawahnya. Grup ini membuat fitur BOS lebih rapi dan memberi ruang jika nanti ada menu BOS lain.

## Model Data

### Tabel `lpj_bos`

Menyimpan data kegiatan ringan untuk satu Kuitansi BOS.

Kolom:

- `id`
- `kuitansi_id` foreign key ke `kuitansis.id`, unique
- `nama_kegiatan`
- `tanggal_kegiatan`
- `lokasi`
- `catatan` nullable
- `created_at`
- `updated_at`

Relasi:

- `LpjBos belongsTo Kuitansi`
- `Kuitansi hasOne LpjBos`

Aturan:

- Satu Kuitansi BOS hanya boleh memiliki satu LPJ BOS.
- Saat membuat LPJ, `kuitansi_id` wajib valid dan belum pernah dipakai oleh LPJ lain.

### Tabel `lpj_bos_attachments`

Menyimpan semua lampiran LPJ BOS.

Kolom:

- `id`
- `lpj_bos_id` foreign key ke `lpj_bos.id`
- `kategori` enum/string terbatas: `foto`, `kwitansi`, `undangan`
- `file_path`
- `original_name`
- `mime_type`
- `file_size`
- `keterangan` nullable
- `sort_order`
- `created_at`
- `updated_at`

Relasi:

- `LpjBos hasMany LpjBosAttachment`
- `LpjBosAttachment belongsTo LpjBos`

Aturan:

- Satu LPJ dapat memiliki banyak lampiran di setiap kategori.
- Lampiran dapat memiliki keterangan opsional.
- `sort_order` dipakai untuk urutan manual tampil/cetak.

## Status Kelengkapan

Status kelengkapan tidak disimpan manual. Status dihitung dari attachment yang ada.

```text
Lengkap = minimal 1 lampiran kategori foto + minimal 1 lampiran kategori kwitansi
Belum lengkap = belum ada foto atau belum ada kwitansi
```

Kategori `undangan` tidak mempengaruhi status karena surat undangan opsional.

## UI dan Alur Pengguna

### Halaman Utama LPJ BOS

Route halaman utama direncanakan sebagai `lpj-bos.index` dengan URL `/lpj-bos`, berada di bawah middleware `auth` dan `role:admin`.

Halaman ini menampilkan **semua Kuitansi BOS**, bukan hanya LPJ yang sudah dibuat. Tujuannya agar admin langsung melihat kuitansi mana yang sudah atau belum punya LPJ.

Kolom utama:

- nomor bukti lengkap
- tahun anggaran
- penerima
- uraian pembayaran atau nama kegiatan jika LPJ sudah ada
- nominal kuitansi
- status LPJ: `Belum Ada LPJ` atau `Ada LPJ`
- status kelengkapan: `Lengkap` atau `Belum lengkap`
- jumlah lampiran kategori foto
- jumlah lampiran kategori kwitansi
- jumlah lampiran kategori undangan
- aksi

Aksi saat belum ada LPJ:

- `Buat LPJ`

Aksi saat sudah ada LPJ:

- `Edit LPJ`
- `Lampiran`
- `Cetak LPJ`
- `Hapus LPJ`

### Modal Data LPJ

Modal digunakan untuk membuat atau mengedit data kegiatan ringan.

Field:

- `nama_kegiatan` wajib
- `tanggal_kegiatan` wajib
- `lokasi` wajib
- `catatan` nullable

Setelah membuat LPJ, admin dapat lanjut ke halaman detail lampiran.

### Halaman Detail LPJ

Route detail direncanakan sebagai `lpj-bos.show` dengan URL `/lpj-bos/{id}`.

Halaman detail dipakai untuk mengelola lampiran banyak file.

Fitur:

- upload multi-file per kategori: foto, kwitansi, undangan
- preview gambar
- download file
- hapus file
- edit keterangan opsional per file
- atur urutan manual dengan tombol naik/turun
- cetak PDF per LPJ

## Filter dan Rekap

Halaman utama LPJ BOS menyediakan filter gabungan:

- pencarian kegiatan, kuitansi, penerima, atau uraian pembayaran
- tahun anggaran
- tanggal kegiatan awal
- tanggal kegiatan akhir
- status kelengkapan: semua, lengkap, atau belum lengkap

PDF rekap menggunakan filter aktif dari halaman daftar.

Isi PDF rekap:

- daftar LPJ sesuai filter
- total LPJ
- total LPJ lengkap
- total LPJ belum lengkap
- total nominal dari kuitansi terkait
- jumlah lampiran per kategori

## Upload dan Penyimpanan File

Format file yang diterima:

- `jpg`
- `jpeg`
- `png`
- `pdf`

Batas ukuran:

- gambar maksimal 10 MB per file
- PDF maksimal 5 MB per file

Penyimpanan mengikuti pola project saat ini, yaitu disk `public`.

Struktur path:

```text
storage/app/public/lpj-bos/{lpj_id}/foto/...
storage/app/public/lpj-bos/{lpj_id}/kwitansi/...
storage/app/public/lpj-bos/{lpj_id}/undangan/...
```

Record attachment menyimpan `file_path` relatif terhadap disk `public`.

## Kompres Gambar

Gambar upload dikompres otomatis menggunakan dependency image processing, misalnya `intervention/image`.

Aturan kompres awal:

- resize maksimal lebar 1600px
- kualitas sekitar 75%
- hasil disimpan sebagai file terkompresi di disk `public`
- file original tidak perlu disimpan terpisah pada fase ini

PDF upload:

- hanya divalidasi ukuran maksimal 5 MB
- disimpan apa adanya
- tidak dikompres otomatis pada fase ini

## PDF Per LPJ

PDF per LPJ dibuat dengan DomPDF, mengikuti pola fitur Kuitansi BOS yang sudah ada.

Isi PDF per LPJ:

1. Ringkasan kuitansi:
   - nomor bukti
   - tahun anggaran
   - penerima
   - jumlah uang
   - uraian pembayaran
   - tanggal lunas
2. Detail kegiatan:
   - nama kegiatan
   - tanggal kegiatan
   - lokasi
   - catatan
3. Status kelengkapan:
   - lengkap atau belum lengkap
   - jumlah foto, kwitansi, dan undangan
4. Lampiran:
   - gambar ditampilkan penuh sesuai kategori dan urutan manual
   - PDF upload dicantumkan sebagai daftar file, berisi nama file dan keterangan jika ada

Catatan teknis:

- File PDF upload tidak digabung ke PDF final pada fase ini.
- Jika user ingin isi PDF upload tampil penuh di dokumen final, file tersebut perlu diupload sebagai gambar atau fitur merge/convert PDF dibuat pada fase lanjutan.

## PDF Rekap LPJ

PDF rekap dibuat dari filter halaman utama.

Isi rekap:

- judul laporan
- informasi filter yang digunakan
- ringkasan total:
  - jumlah LPJ
  - jumlah lengkap
  - jumlah belum lengkap
  - total nominal
- tabel LPJ:
  - nomor bukti
  - tahun anggaran
  - nama kegiatan
  - tanggal kegiatan
  - penerima
  - nominal
  - status kelengkapan
  - jumlah lampiran per kategori

## Error Handling dan Validasi

Validasi LPJ:

- `kuitansi_id` wajib valid
- `kuitansi_id` harus unik di `lpj_bos`
- `nama_kegiatan` wajib dan dibatasi panjangnya
- `tanggal_kegiatan` wajib berupa tanggal valid
- `lokasi` wajib dan dibatasi panjangnya
- `catatan` opsional

Validasi lampiran:

- kategori wajib salah satu dari `foto`, `kwitansi`, `undangan`
- file wajib bertipe `jpg`, `jpeg`, `png`, atau `pdf`
- gambar maksimal 10 MB
- PDF maksimal 5 MB
- keterangan opsional

Saat upload gagal:

- record attachment tidak dibuat
- file yang gagal diproses tidak disimpan permanen
- pesan error menjelaskan penyebab: format salah, ukuran terlalu besar, atau kompres gagal

Saat hapus lampiran:

- file di storage dihapus
- record attachment dihapus

Saat hapus LPJ:

- semua file lampiran terkait dihapus dari storage
- semua record attachment terkait dihapus
- record LPJ dihapus

## Dampak ke Fitur Kuitansi BOS

Model `Kuitansi` akan mendapatkan relasi `lpjBos`.

Halaman Kuitansi BOS dapat ditambah indikator atau tombol cepat ke LPJ jika implementasi dianggap perlu. Namun kebutuhan utama berada di halaman `LPJ BOS`, yang sudah menampilkan semua kuitansi dan status LPJ.

## Komponen yang Direncanakan

Backend/model:

- `App\Models\LpjBos`
- `App\Models\LpjBosAttachment`
- relasi tambahan di `App\Models\Kuitansi`

Livewire:

- `App\Livewire\LpjBosManagement`
- `App\Livewire\LpjBosDetail`

Controller PDF/download:

- `App\Http\Controllers\LpjBosController`

Views:

- `resources/views/livewire/lpj-bos-management.blade.php`
- `resources/views/livewire/lpj-bos-detail.blade.php`
- `resources/views/pdf/lpj-bos.blade.php`
- `resources/views/pdf/lpj-bos-rekap.blade.php`

Migrations:

- create `lpj_bos`
- create `lpj_bos_attachments`

Routes:

- `GET /lpj-bos`
- `GET /lpj-bos/print-rekap`
- `GET /lpj-bos/{id}`
- `GET /lpj-bos/{id}/print`

Route `print-rekap` didefinisikan sebelum route `{id}` agar tidak konflik dengan parameter numerik/detail.

Dependency:

- `intervention/image` untuk resize dan kompres gambar

## Testing

Minimal pengujian:

- relasi `Kuitansi hasOne LpjBos`
- relasi `LpjBos hasMany LpjBosAttachment`
- create LPJ untuk kuitansi yang belum punya LPJ
- mencegah create LPJ ganda untuk satu kuitansi
- edit data LPJ
- hapus LPJ dan memastikan attachment serta file storage ikut terhapus
- upload gambar valid dan memastikan record attachment dibuat
- upload PDF valid dan memastikan record attachment dibuat
- menolak file format tidak valid
- menolak gambar di atas 10 MB
- menolak PDF di atas 5 MB
- status lengkap jika ada minimal satu foto dan satu kwitansi
- status belum lengkap jika salah satu kategori wajib belum ada
- urutan manual lampiran berubah sesuai aksi naik/turun
- PDF per LPJ dapat dibuat
- PDF rekap mengikuti filter yang diberikan

## Risiko dan Mitigasi

### Risiko: Ukuran PDF besar jika banyak gambar

Mitigasi:

- kompres gambar saat upload
- resize maksimal lebar 1600px
- gunakan kualitas sekitar 75%
- template PDF menampilkan gambar dengan ukuran terkendali

### Risiko: PDF upload tidak tampil penuh di PDF final

Mitigasi:

- fase ini mencantumkan PDF upload sebagai daftar lampiran
- dokumentasikan bahwa file PDF dapat di-download dari aplikasi
- fitur merge/convert PDF dicatat sebagai fase lanjutan terpisah, bukan bagian dari scope implementasi ini

### Risiko: File storage tertinggal saat record dihapus

Mitigasi:

- hapus file storage saat lampiran dihapus
- hapus semua file lampiran saat LPJ dihapus
- gunakan service/helper terpusat untuk operasi file jika implementasi mulai kompleks

## Kriteria Sukses

Fitur dianggap selesai jika:

- admin dapat membuka menu `BOS > LPJ BOS`
- halaman LPJ BOS menampilkan semua Kuitansi BOS beserta status LPJ
- admin dapat membuat dan mengedit LPJ untuk satu kuitansi
- admin dapat upload banyak foto, kwitansi, dan undangan
- gambar upload dikompres otomatis
- admin dapat memberi keterangan opsional pada lampiran
- admin dapat mengatur urutan lampiran secara manual
- status kelengkapan otomatis tampil benar
- admin dapat mencetak PDF per LPJ
- admin dapat mencetak PDF rekap LPJ berdasarkan filter
- file lampiran terhapus dari storage saat attachment atau LPJ dihapus
