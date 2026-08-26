# Desain Fitur Copy SK GTY MI

Tanggal: 26 Agustus 2026
Status: Menunggu persetujuan spesifikasi tertulis

## Tujuan

Mempercepat pembuatan SK GTY MI baru dari SK yang sudah selesai. Pengguna dapat menyalin konfigurasi SK lama, lalu cukup memilih guru baru dan mengisi jabatan penerima sebelum menyimpan.

## Ruang Lingkup

Fitur diterapkan khusus pada modul SK GTY MI di proyek `data-induk`:

- Komponen: `App\Livewire\SkGtyMiManagement`
- Tampilan: `resources/views/livewire/sk-gty-mi-management.blade.php`
- Model: `App\Models\SkGtyMi`

Fitur tidak mengubah PDF, format nomor SK, aturan validasi utama, maupun SK sumber.

## Alur Pengguna

1. Pengguna membuka daftar SK GTY MI.
2. Pada kolom Aksi, pengguna menekan tombol `Copy SK` pada salah satu baris.
3. Aplikasi memuat SK sumber dan membuka modal form dalam mode salinan.
4. Form menampilkan keterangan bahwa data berasal dari nomor SK tertentu.
5. Pengguna memilih guru baru.
6. Saat guru dipilih, nama, tempat lahir, tanggal lahir, dan NUPTK mengikuti data master guru baru.
7. Pengguna mengisi jabatan penerima baru.
8. Pengguna memeriksa field template lain dan dapat mengubahnya bila diperlukan.
9. Saat tombol Simpan ditekan, aplikasi membuat record SK baru.
10. SK sumber tidak berubah.

Tidak ada record baru yang dibuat hanya dengan menekan tombol Copy SK.

## Field yang Disalin

Field berikut disalin dari SK sumber ke form:

- `tanggal_sk`
- `tanggal_musyawarah`
- `pendidikan_terakhir`
- `berlaku_mulai`
- `berlaku_sampai`
- `penandatangan_nama`
- `penandatangan_jabatan`
- `tempat_penetapan`
- `tanggal_penetapan`

Data identitas guru pada SK sumber boleh tampil hanya sebagai referensi salinan, tetapi tidak menjadi penerima SK baru.

## Field yang Tidak Disalin

- `id` / `skId`
- `guru_mi_id`
- `selectedGuru`
- `searchGuru`
- `tempat_lahir`
- `tanggal_lahir`
- `nuptk`
- `jabatan`
- `status`
- `nomor_sk` lama

Nilai penggantinya:

- `skId = null`
- `guru_mi_id = null`
- `selectedGuru = null`
- `nomor_sk = SkGtyMi::generateNomorSk()`
- `jabatan = null`
- `status = draft`

Dengan demikian, nama penerima dan jabatan lama tidak dapat ikut tersimpan secara tidak sengaja.

## Perilaku Pemilihan Guru

Fungsi `selectGuru()` tetap menjadi sumber pengisian identitas penerima:

- `guru_mi_id` diisi dari guru terpilih.
- `selectedGuru` menampilkan nama, NUPTK, dan status pegawai.
- `tempat_lahir`, `tanggal_lahir`, dan `nuptk` ditimpa dengan data guru baru.
- `pendidikan_terakhir` tetap berasal dari template SK sumber karena field tersebut belum diambil oleh `selectGuru()` pada implementasi saat ini.
- `jabatan` tetap kosong dan wajib diperiksa/diisi manual sesuai kebutuhan SK.

## State Mode Form

Komponen menambahkan state mode yang eksplisit:

- `isEditing = false` untuk hasil copy karena save harus membuat record baru.
- `isCopying = true` saat modal berasal dari Copy SK.
- `copiedFromNomorSk` menyimpan nomor SK sumber untuk informasi pada modal, bukan untuk persistensi.

Mode form:

| Mode | Judul modal | Operasi save |
|---|---|---|
| Create | Tambah SK GTY MI | Create |
| Edit | Edit SK GTY MI | Update |
| Copy | Copy SK GTY MI | Create |

`resetForm()` dan `closeModal()` wajib menghapus state copy agar tidak bocor ke pembukaan modal berikutnya.

## Antarmuka

### Tombol Copy

- Diletakkan pada kolom Aksi di setiap baris.
- Selalu tersedia untuk SK draft, aktif, maupun tidak aktif karena copy hanya membuka form baru.
- Menggunakan elemen `button` dan `wire:click="openCopyModal(id)"`.
- Memiliki ikon duplikat yang relevan.
- Memiliki `title="Copy SK"` dan `aria-label` yang menyebut nomor SK sumber.
- Target interaksi mengikuti ukuran tombol aksi yang sudah ada.

### Modal Copy

Pada mode copy:

- Judul: `Copy SK GTY MI`.
- Subteks: `Salinan dari SK [nomor sumber]. Pilih guru baru dan periksa jabatan sebelum menyimpan.`
- Tombol submit tetap `Simpan` atau dapat memakai `Simpan sebagai SK Baru` untuk memperjelas hasil operasi.
- Field guru menampilkan kondisi belum dipilih.
- Field jabatan kosong.
- Nomor SK baru terlihat dan tetap dapat diperiksa sesuai perilaku form saat ini.

## Error Handling

- ID SK sumber yang tidak ditemukan menggunakan `findOrFail()` dan tidak membuka modal dengan data parsial.
- Jika guru pada SK sumber sudah terhapus, copy tetap dapat berjalan karena identitas guru sumber tidak diperlukan untuk membuat template baru.
- Nomor baru dibuat saat modal copy dibuka. Validasi uniqueness yang sudah berlaku tetap menjadi lapisan perlindungan saat save.
- Validasi gagal mempertahankan form dalam mode copy dan menampilkan error field seperti biasa.
- Menutup modal tidak membuat record baru.

## Keamanan dan Integritas Data

- Operasi copy hanya membaca record sumber.
- Tidak ada perubahan atau timestamp update pada SK sumber.
- Save mode copy selalu memanggil `SkGtyMi::create()`, bukan `update()`.
- Status awal salinan selalu `draft`.
- Relasi guru wajib dipilih ulang melalui validasi `exists:guru_mis,id`.
- Field penerima lama tidak boleh ikut terisi dari SK sumber.

## Pengujian

Test Livewire mencakup:

1. Tombol Copy tersedia pada setiap baris daftar.
2. `openCopyModal()` membuka modal dalam mode copy.
3. Nomor SK hasil copy berbeda dari nomor sumber.
4. Field template yang ditentukan tersalin dengan benar.
5. `guru_mi_id`, `selectedGuru`, identitas guru, dan `jabatan` kosong.
6. Status hasil copy adalah `draft`.
7. Memilih guru baru mengisi identitas guru baru tanpa mengembalikan guru lama.
8. Save membuat record baru dan jumlah record bertambah satu.
9. SK sumber tetap identik dan `updated_at` tidak berubah.
10. Menutup modal tidak membuat record baru.
11. Membuka Create/Edit setelah Copy tidak membawa state `isCopying` atau nomor sumber.
12. ID sumber yang tidak ada menghasilkan 404/model-not-found sesuai perilaku Livewire.

## Batas Lingkup

Tidak termasuk:

- Copy massal beberapa SK sekaligus.
- Template SK permanen terpisah.
- Copy lintas jenis SK atau lintas jenjang MI/SMP.
- Otomatis mengaktifkan SK hasil copy.
- Mengubah PDF atau konten keputusan.
- Mengubah algoritma nomor SK yang sudah ada.
- Menghapus kewajiban pengguna memeriksa data sebelum menyimpan.

## Keputusan dan Alasan

- Form dibuka sebelum persistensi agar tidak membuat record tidak lengkap dari satu klik.
- Guru dan jabatan dikosongkan karena dua field itu merupakan tujuan utama perubahan pada SK baru.
- Status dipaksa `draft` agar salinan tidak langsung berlaku sebagai SK aktif.
- Nomor dibuat melalui generator existing agar konsisten dengan aturan proyek.
- State copy dibuat eksplisit agar operasi create, edit, dan copy tidak bergantung pada inferensi field yang rapuh.
