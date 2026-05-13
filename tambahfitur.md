Anda adalah senior full-stack Laravel engineer yang ahli Laravel, Livewire 3, Blade, Tailwind/Bootstrap sesuai style project, Eloquent, migration, authorization, dan struktur aplikasi yang rapi.

Saya punya aplikasi yang sudah berjalan dengan tech stack Laravel + Livewire 3. Saya ingin menambahkan fitur baru bernama:

“Nilai Ijazah Kelas 6”

Tolong implementasikan fitur ini dengan mengikuti struktur, konvensi, komponen, naming, layout, sidebar/menu, style UI, authorization, dan pola coding yang sudah ada di project. Jangan mengubah fitur lama yang tidak terkait. Sebelum membuat kode, baca dulu struktur project, model, migration, route, layout, komponen Livewire, dan pola menu yang sudah ada.

## Tujuan Fitur

User bisa mengelola nilai ijazah untuk siswa kelas 6 berdasarkan tahun ajaran. Alurnya:

1. User klik menu “Nilai Ijazah Kelas 6”.
2. User melihat daftar tahun ajaran.
3. User bisa membuat tahun ajaran baru.
4. User klik salah satu tahun ajaran.
5. Di dalam detail tahun ajaran terdapat 3 menu/tab utama:
    - Input Nilai Rata-rata Raport Kelas 6
    - Input Nilai UM / Ujian Madrasah
    - Cetak
6. Menu cetak berisi:
    - Cetak cover depan ijazah dummy
    - Cetak nilai ijazah

## Detail Menu

### 1. Menu Utama: Nilai Ijazah Kelas 6

Tambahkan menu di sidebar/navigation sesuai pola project yang sudah ada.

Nama menu:
“Nilai Ijazah Kelas 6”

Route disarankan:

- /nilai-ijazah-kelas-6
- /nilai-ijazah-kelas-6/{tahunAjaran}

Gunakan route dan middleware yang sesuai dengan project.

Halaman utama menampilkan daftar tahun ajaran yang pernah dibuat, misalnya:

| No  | Tahun Ajaran | Jumlah Siswa | Status | Aksi                  |
| --- | ------------ | ------------ | ------ | --------------------- |
| 1   | 2025/2026    | 32           | Aktif  | Detail / Edit / Hapus |

Fitur:

- Tambah tahun ajaran
- Edit tahun ajaran
- Hapus tahun ajaran jika belum dipakai, atau gunakan soft delete jika project sudah memakai soft delete
- Klik tahun ajaran untuk masuk ke detail

Field minimal tahun ajaran:

- nama_tahun_ajaran, contoh: 2025/2026
- status aktif/nonaktif jika dibutuhkan
- timestamps

Jika project sudah punya tabel tahun ajaran, gunakan tabel yang sudah ada. Jangan membuat tabel duplikat jika sudah tersedia.

### 2. Detail Tahun Ajaran

Setelah user klik tahun ajaran, tampilkan halaman detail dengan 3 tab/menu:

A. Input Nilai Rata-rata Raport  
B. Input Nilai UM / Ujian Madrasah  
C. Cetak

Gunakan Livewire 3 component sesuai style project.

---

## A. Input Nilai Rata-rata Raport

Menu ini digunakan untuk input nilai rata-rata raport siswa kelas 6 berdasarkan riwayat nilai:

- Kelas 4 Semester 1
- Kelas 4 Semester 2
- Kelas 5 Semester 1
- Kelas 5 Semester 2
- Kelas 6 Semester 1

Data diinput per siswa dan per mata pelajaran.

Contoh kolom tabel:

| No  | NISN | Nama Siswa | Mapel | K4 S1 | K4 S2 | K5 S1 | K5 S2 | K6 S1 | Rata-rata Raport |
| --- | ---- | ---------- | ----- | ----- | ----- | ----- | ----- | ----- | ---------------- |

Rumus:
Rata-rata Raport = (K4 S1 + K4 S2 + K5 S1 + K5 S2 + K6 S1) / 5

Validasi:

- Nilai wajib angka
- Rentang nilai 0 sampai 100
- Boleh desimal, contoh 89.5
- Jika ada nilai kosong, tampilkan sebagai kosong/null dan jangan hitung final sampai semua komponen nilai lengkap
- Gunakan pembulatan 2 angka di belakang koma untuk tampilan

Fitur:

- Filter/search siswa
- Filter mata pelajaran jika diperlukan
- Input nilai dalam bentuk tabel/grid
- Tombol simpan
- Tampilkan notifikasi sukses/error sesuai style project
- Pastikan update tidak membuat data duplikat
- Gunakan unique constraint agar satu siswa + satu mapel + satu tahun ajaran hanya punya satu record nilai ijazah

Jika project sudah punya data siswa, rombel, kelas, dan mapel:

- Gunakan tabel/model yang sudah ada
- Ambil siswa kelas 6 pada tahun ajaran terkait
- Ambil daftar mata pelajaran sesuai data project

Jika project belum punya relasi yang jelas:

- Buat implementasi yang adaptif dan tidak merusak data lama
- Tambahkan komentar/TODO seperlunya di kode untuk mapping data siswa/mapel bila diperlukan

---

## B. Input Nilai UM / Ujian Madrasah

Menu ini digunakan untuk input nilai UM per siswa dan per mata pelajaran.

Contoh kolom tabel:

| No  | NISN | Nama Siswa | Mapel | Nilai UM |
| --- | ---- | ---------- | ----- | -------- |

Validasi:

- Nilai UM wajib angka jika akan dicetak final
- Rentang 0 sampai 100
- Boleh desimal
- Gunakan pembulatan 2 angka di belakang koma untuk tampilan

Fitur:

- Search siswa
- Filter mata pelajaran jika diperlukan
- Input grid/tabel
- Tombol simpan
- Notifikasi sukses/error
- Tidak membuat data duplikat

---

## C. Menu Cetak

Menu cetak berisi 2 tombol:

1. Cetak Cover Depan Ijazah Dummy
2. Cetak Nilai Ijazah

Gunakan blade print view yang rapi dan CSS khusus print. Jika project sudah punya package PDF seperti dompdf/snappy/mpdf, ikuti pola yang sudah ada. Jika belum ada, cukup buat halaman print-friendly dengan window.print(), jangan menambah dependency PDF baru tanpa kebutuhan.

### C.1 Cetak Cover Depan Ijazah Dummy

Buat halaman cetak cover depan ijazah dummy.

Isi minimal:

- Judul: IJAZAH
- Tulisan dummy/watermark: DUMMY / CONTOH
- Nama madrasah/sekolah, ambil dari setting project jika ada
- Tahun ajaran
- Nama siswa
- NISN/NIS
- Tempat tanggal lahir jika data ada
- Nomor peserta jika data ada
- Layout menyerupai cover depan ijazah sederhana dan rapi

Karena ini dummy, tampilkan label jelas:
“DOKUMEN DUMMY - BUKAN IJAZAH RESMI”

Bisa cetak per siswa atau semua siswa, sesuaikan dengan pola UI yang paling rapi.

### C.2 Cetak Nilai Ijazah

Buat halaman cetak nilai ijazah.

Perhitungan nilai ijazah:

Nilai Rata-rata Raport memiliki bobot 70%.
Nilai UM / Ujian Madrasah memiliki bobot 30%.

Rumus per mata pelajaran:

Rata-rata Raport = (K4 S1 + K4 S2 + K5 S1 + K5 S2 + K6 S1) / 5

Nilai Ijazah = (Rata-rata Raport x 70%) + (Nilai UM x 30%)

Atau:

Nilai Ijazah = (Rata-rata Raport _ 0.7) + (Nilai UM _ 0.3)

Contoh tabel cetak nilai:

| No  | Mata Pelajaran | K4 S1 | K4 S2 | K5 S1 | K5 S2 | K6 S1 | Rata-rata Raport | UM  | Nilai Ijazah |
| --- | -------------- | ----- | ----- | ----- | ----- | ----- | ---------------- | --- | ------------ |

Tambahkan:

- Identitas siswa
- Nama madrasah/sekolah
- Tahun ajaran
- Tanggal cetak
- Tempat tanda tangan kepala madrasah/sekolah jika data ada
- Total rata-rata akhir semua mata pelajaran jika diperlukan

Pembulatan:

- Semua nilai tampil 2 angka di belakang koma
- Nilai akhir ijazah tampil 2 angka di belakang koma
- Jangan membulatkan data mentah di database jika tidak diperlukan; pembulatan cukup saat display/print

Jika ada nilai belum lengkap:

- Tampilkan tanda “Belum Lengkap”
- Jangan tampilkan nilai final sebagai angka palsu
- Beri indikator pada halaman cetak bahwa data belum lengkap

---

## Struktur Database yang Diinginkan

Cek dulu apakah project sudah memiliki tabel:

- tahun ajaran
- siswa
- kelas/rombel
- mata pelajaran
- setting sekolah/madrasah

Gunakan yang sudah ada jika tersedia.

Jika belum ada tabel khusus nilai ijazah, buat migration baru. Nama tabel boleh menyesuaikan convention project. Contoh struktur:

Table: nilai_ijazah_tahun_ajarans

- id
- nama_tahun_ajaran
- status nullable/boolean default true
- created_by nullable jika project punya user tracking
- updated_by nullable jika project punya user tracking
- timestamps
- softDeletes jika project umum menggunakan soft delete

Table: nilai_ijazah_scores

- id
- nilai_ijazah_tahun_ajaran_id
- siswa_id
- mata_pelajaran_id
- kelas_4_semester_1 nullable decimal(5,2)
- kelas_4_semester_2 nullable decimal(5,2)
- kelas_5_semester_1 nullable decimal(5,2)
- kelas_5_semester_2 nullable decimal(5,2)
- kelas_6_semester_1 nullable decimal(5,2)
- nilai_um nullable decimal(5,2)
- timestamps

Tambahkan unique index:

- nilai_ijazah_tahun_ajaran_id
- siswa_id
- mata_pelajaran_id

Foreign key:

- nilai_ijazah_tahun_ajaran_id ke tabel tahun ajaran nilai ijazah
- siswa_id ke tabel siswa yang sudah ada
- mata_pelajaran_id ke tabel mata pelajaran yang sudah ada

Jika nama tabel siswa/mapel di project berbeda, sesuaikan dengan model yang ada.

Jangan menyimpan nilai rata-rata raport dan nilai ijazah final secara permanen kecuali project memang membutuhkan cache. Lebih baik hitung melalui accessor/service agar konsisten.

Namun jika performa membutuhkan penyimpanan hasil hitung, buat service khusus dan pastikan nilainya selalu dihitung ulang setiap kali nilai komponen berubah.

---

## Model dan Logic

Buat atau sesuaikan model:

- NilaiIjazahTahunAjaran
- NilaiIjazahScore

Tambahkan relasi:

- tahun ajaran hasMany scores
- score belongsTo siswa
- score belongsTo mata pelajaran
- score belongsTo tahun ajaran

Tambahkan method/accessor di model atau service:

getRataRataRaportAttribute():

- Jika salah satu dari 5 nilai raport kosong, return null
- Jika lengkap, return rata-rata 5 nilai

getNilaiIjazahAttribute():

- Jika rata-rata raport null atau nilai_um null, return null
- Jika lengkap, return (rata_rata_raport _ 0.7) + (nilai_um _ 0.3)

Buat service bila lebih rapi, misalnya:
App\Services\NilaiIjazahCalculator

Method:

- rataRataRaport($score): ?float
- nilaiIjazah($score): ?float
- isComplete($score): bool

Pastikan formula hanya ditulis di satu tempat agar tidak duplikatif.

---

## Livewire Components

Buat komponen Livewire 3 sesuai struktur project. Contoh:

- App\Livewire\NilaiIjazahKelas6\Index
- App\Livewire\NilaiIjazahKelas6\Show
- App\Livewire\NilaiIjazahKelas6\InputRaport
- App\Livewire\NilaiIjazahKelas6\InputUm
- App\Livewire\NilaiIjazahKelas6\Cetak

Atau gunakan struktur lain yang sesuai dengan project.

Requirement Livewire:

- Gunakan #[Layout(...)] jika project memakai attribute layout
- Gunakan pagination jika data siswa banyak
- Gunakan wire:model.live/debounce sesuai kebutuhan
- Validasi server-side wajib ada
- Jangan hanya mengandalkan validasi frontend
- Gunakan transaction saat menyimpan banyak nilai
- Hindari query N+1 dengan eager loading

---

## UI/UX

Ikuti style UI project yang sudah ada.

Minimal UI:

- Breadcrumb
- Judul halaman
- Card daftar tahun ajaran
- Modal/form tambah dan edit tahun ajaran
- Tab detail tahun ajaran
- Tabel input nilai yang nyaman
- Search siswa
- Tombol simpan
- Tombol kembali
- Tombol cetak
- Badge status lengkap/belum lengkap
- Alert/notification sukses/error

Untuk input nilai:

- Gunakan input type number
- step="0.01"
- min="0"
- max="100"

Untuk tabel besar:

- Buat tampilan tetap rapi di desktop
- Tambahkan horizontal scroll jika kolom banyak

---

## Authorization dan Keamanan

Ikuti sistem auth project.

Jika project sudah menggunakan role/permission:

- Tambahkan permission sesuai pola project, misalnya:
    - nilai-ijazah.view
    - nilai-ijazah.create
    - nilai-ijazah.update
    - nilai-ijazah.delete
    - nilai-ijazah.print

Jika belum ada permission:

- Minimal pastikan route berada di middleware auth
- Jangan expose halaman cetak tanpa auth kecuali project punya pola signed URL

Validasi semua input:

- Tahun ajaran required
- Nilai numeric 0-100
- siswa_id dan mata_pelajaran_id harus valid
- Cegah mass assignment vulnerability dengan fillable/guarded yang benar

---

## Testing

Tambahkan test bila project sudah punya struktur testing.

Minimal test:

1. Bisa membuat tahun ajaran nilai ijazah.
2. Bisa menyimpan nilai raport.
3. Bisa menyimpan nilai UM.
4. Formula rata-rata raport benar.
5. Formula nilai ijazah benar.
6. Jika ada nilai kosong, nilai final return null/belum lengkap.
7. Tidak bisa input nilai di bawah 0 atau di atas 100.
8. Unique data per tahun ajaran + siswa + mapel.

Contoh perhitungan test:

K4 S1 = 80
K4 S2 = 82
K5 S1 = 84
K5 S2 = 86
K6 S1 = 88

Rata-rata Raport = (80 + 82 + 84 + 86 + 88) / 5 = 84

Nilai UM = 90

Nilai Ijazah = (84 _ 0.7) + (90 _ 0.3)
Nilai Ijazah = 58.8 + 27
Nilai Ijazah = 85.8

---

## Output yang Saya Harapkan dari Agent

Kerjakan langsung implementasi di codebase.

Sebelum coding:

1. Inspect struktur project.
2. Temukan pola route, layout, sidebar, Livewire component, model, migration, dan permission.
3. Buat rencana implementasi singkat.
4. Setelah itu implementasikan.

Setelah coding:

1. Jelaskan file apa saja yang dibuat/diubah.
2. Jelaskan cara menjalankan migration.
3. Jelaskan cara mengakses menu.
4. Jelaskan cara mengetes fitur.
5. Jalankan formatter/linter/test yang tersedia di project jika ada.
6. Pastikan tidak ada error syntax.

Jangan melakukan perubahan destruktif.
Jangan menghapus fitur lama.
Jangan mengganti struktur besar aplikasi tanpa alasan.
Jangan menambah package baru kecuali benar-benar diperlukan dan jelaskan alasannya.
Gunakan pendekatan yang paling sesuai dengan Laravel + Livewire 3 dan convention project yang sudah ada.

Catatan penting:
Jika terdapat perbedaan nama tabel/model di project saya, jangan memaksakan nama dari prompt. Sesuaikan dengan struktur yang sudah ada. Prioritaskan integrasi yang natural dengan aplikasi lama.

Rata-rata Raport = (K4 S1 + K4 S2 + K5 S1 + K5 S2 + K6 S1) / 5

Nilai Ijazah = (Rata-rata Raport x 70%) + (Nilai UM x 30%)
