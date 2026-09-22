# Desain Fitur Validasi Data Siswa (Publik, Wali Kelas)

Tanggal: 15 September 2026
Status: Menunggu persetujuan spesifikasi tertulis

## Tujuan

Memberi cara cepat bagi wali kelas untuk mengecek kelengkapan data siswa di kelasnya sendiri, tanpa perlu akun login. Wali kelas membuka landing page publik, masuk PIN akses, pilih sekolah + kelas, lihat daftar nama & tanggal lahir siswa aktif di kelas itu, lalu menandai **Lengkap** atau mencatat siswa yang belum ada di sistem. Hasilnya masuk ke halaman admin untuk ditindaklanjuti.

## Ruang Lingkup

- Section publik baru di `resources/views/welcome.blade.php`, menggantikan section FAQ (`#faq`) sepenuhnya — termasuk link di nav desktop, nav mobile, dan footer.
- Komponen publik: `App\Livewire\ValidasiDataSiswaForm` (di-embed langsung via `@livewire`, tanpa route sendiri).
- Komponen admin: `App\Livewire\ValidasiDataSiswaManagement`, route baru `validasi-data-siswa` (`auth` + `role:admin`), ditambahkan ke sidebar `resources/views/layouts/admin.blade.php`.
- Model baru: `App\Models\ValidasiDataSiswa` (tabel `validasi_data_siswas`).
- Tambahan field PIN di `SchoolSetting` (key `validasi_data_siswa_pin`), diatur lewat halaman Pengaturan Sekolah (`App\Livewire\SchoolSettingsManagement`) yang sudah ada.
- Sumber data siswa: `App\Models\SiswaMi` dan `App\Models\SiswaSmp` yang sudah ada — **tidak membuat model/tabel siswa baru**, hanya membaca `nama_lengkap`, `tanggal_lahir`, `tingkat_rombel`, `status`.

Tidak termasuk (lihat juga Batas Lingkup): login guru, model Kelas/Rombel baru, multi-tenancy (`yayasan_id`), PIN per kelas, riwayat submission (history), notifikasi otomatis (email/WA) ke admin.

## Alur Pengguna (Wali Kelas)

1. Wali kelas membuka landing page publik, klik menu **Data Siswa** (pengganti FAQ) di nav.
2. Section menampilkan form PIN. Wali kelas memasukkan PIN yang sudah diberi tahu admin (lisan/WA).
3. PIN benar → tampil pilihan **Sekolah** (MI/SMP) lalu **Kelas** (dropdown distinct `tingkat_rombel` dari siswa berstatus Aktif di sekolah terpilih). Tiap opsi kelas menampilkan badge status validasi terakhir jika sudah pernah diisi (Lengkap / Ada Catatan / belum ada badge jika belum pernah).
4. Setelah kelas dipilih, tampil tabel siswa aktif di kelas itu: kolom **Nama Lengkap** dan **Tanggal Lahir** saja, urut nama. Field lain (NISN, NIK, alamat, dll) tidak ditampilkan.
5. Wali kelas mengisi **Nama Pengisi** (wajib), lalu memilih salah satu:
   - Tombol **Lengkap** — menandai data kelas ini sudah benar.
   - Isi textarea **Siswa yang belum ada di sistem** lalu tombol **Kirim Catatan** — mencatat nama-nama siswa yang perlu ditambahkan admin.
6. Submit menyimpan/menimpa satu baris `validasi_data_siswas` untuk kombinasi (sekolah, tingkat_rombel) — bukan menambah record baru tiap kali.
7. Tampil pesan sukses. Wali kelas bisa pilih kelas lain (kembali ke langkah 3) tanpa perlu masukkan PIN ulang selama masih di sesi/kunjungan yang sama.

## Gate PIN

- PIN tunggal, berlaku untuk seluruh section (bukan per kelas), disimpan sebagai `SchoolSetting` key `validasi_data_siswa_pin` (plain text, bukan hash — admin perlu membacanya kembali untuk diberi tahu ke wali kelas).
- Diatur admin lewat halaman **Pengaturan Sekolah** yang sudah ada (`SchoolSettingsManagement`), field teks baru "PIN Akses Data Siswa".
- **Fail closed**: jika `validasi_data_siswa_pin` kosong/belum diset admin, section publik menampilkan pesan "Fitur belum diaktifkan admin" dan form PIN tidak diproses — data siswa tidak pernah bocor hanya karena admin lupa set PIN.
- Percobaan PIN salah dihitung lewat Laravel session (`session('validasi_pin_attempts')`, bukan public property Livewire) — supaya reload halaman tidak mereset hitungan dan melewati lockout. Setelah 5 kali salah, section terkunci 5 menit (`session('validasi_pin_locked_until')`).
- PIN benar → `session(['validasi_pin_ok' => true])`. Berlaku selama sesi browser (hilang saat sesi habis/logout browser), jadi wali kelas tidak perlu masukkan PIN ulang tiap ganti kelas dalam satu kunjungan.

## Data Model

```
validasi_data_siswas
- id
- sekolah          enum('MI','SMP')
- tingkat_rombel    string            // nilai sama persis dgn siswa_mis/siswa_smps.tingkat_rombel
- status            enum('belum','lengkap','ada_catatan') default 'belum'
- nama_pengisi      string
- catatan           text nullable     // daftar nama siswa yg belum ada di sistem (free text)
- checked_at        timestamp nullable
- timestamps

unique(sekolah, tingkat_rombel)
```

- Model `ValidasiDataSiswa` — `$fillable` eksplisit, `$casts` untuk `checked_at` → `datetime`.
- Penyimpanan pakai `updateOrCreate(['sekolah' => ..., 'tingkat_rombel' => ...], [...])` — satu kombinasi sekolah+kelas selalu satu baris, submit ulang menimpa baris lama (bukan riwayat).
- **Tidak ada kolom `yayasan_id`.** Dicek: tidak ada satupun tabel di proyek ini (`siswa_mis`, `siswa_smps`, `tracer_alumnis`, dll) yang punya kolom `yayasan_id` atau trait `BelongsToYayasan` — infrastruktur multi-tenancy di CLAUDE.md §3.1 belum diimplementasikan sama sekali di codebase ini. Tabel baru ini mengikuti konvensi yang sudah ada (single-tenant, tanpa scoping) agar konsisten; mengaktifkan tenancy adalah pekerjaan lintas-tabel terpisah, di luar lingkup fitur ini.

## Unduh Data Lengkap (Excel)

Setelah kelas ditandai **Lengkap**, wali kelas bisa mengunduh data siswa kelas itu sebagai `.xlsx` lewat `App\Exports\SiswaKelasPublikExport` (memakai `maatwebsite/excel` yang sudah terpasang).

- Tombol hanya muncul saat kelas berstatus `lengkap`, dan `unduhExcel()` **mengecek ulang status itu di server** — menyembunyikan tombol saja tidak dianggap kontrol akses.
- Berkas hanya berisi siswa berstatus Aktif di kelas yang dipilih, bukan seluruh sekolah.
- **Kolom sensitif sengaja dibuang**: `nik`, `no_telepon`, dan `nomor_kip_pip` tidak ikut, karena berkas ini keluar lewat halaman publik yang hanya dijaga PIN bersama, bukan akun per orang. Kolom yang ikut: No, Nama Lengkap, NISN, Tempat Lahir, Tanggal Lahir, Tingkat - Rombel, Umur, Jenis Kelamin, Alamat, Kebutuhan Khusus, Disabilitas, Nama Ayah/Ibu Kandung, Nama Wali, Status.
- Tiap unduhan dicatat ke tabel `unduhan_data_siswas` (sekolah, tingkat_rombel, nama_pengisi, ip_address, waktu) dan tampil sebagai panel **Riwayat Unduhan** (50 terakhir) di halaman admin — supaya ada jejak siapa yang menarik data bila terjadi penyalahgunaan.
- Unduhan tetap butuh `pinOk`; pemanggilan langsung tanpa PIN menghasilkan 403.

```
unduhan_data_siswas
- id
- sekolah        enum('MI','SMP')
- tingkat_rombel string
- nama_pengisi   string
- ip_address     string(45) nullable
- timestamps
index(sekolah, tingkat_rombel)
```

**Risiko yang diterima sadar:** PIN bersifat global, jadi siapa pun yang pernah menerima PIN dapat mengunduh data kelas manapun yang sudah ditandai lengkap. Mitigasinya adalah pembatasan kolom + log unduhan, bukan isolasi antar-kelas. Bila kebutuhan privasi meningkat, langkah berikutnya adalah PIN per kelas atau login wali kelas.

## Halaman Admin

`App\Livewire\ValidasiDataSiswaManagement`, route `validasi-data-siswa` (`middleware(['auth', 'role:admin'])`, `name('validasi-data-siswa.index')`), layout `layouts.admin`.

- Daftar seluruh kombinasi sekolah+kelas: union antara kelas yang punya siswa aktif (dari `SiswaMi`/`SiswaSmp` distinct `tingkat_rombel`) dan baris `validasi_data_siswas` yang sudah ada — kelas yang belum pernah divalidasi tetap muncul dengan status "Belum Dicek".
- Kolom: Sekolah, Kelas, Status (badge: abu-abu Belum Dicek / hijau Lengkap / kuning Ada Catatan), Nama Pengisi, Catatan, Waktu Cek.
- Filter: sekolah (MI/SMP), status. Search: nama kelas.
- Tombol **Tandai Selesai** pada baris berstatus `ada_catatan` — reset baris itu ke `status = 'belum'`, `catatan = null` (dipakai admin setelah menambahkan siswa yang dilaporkan hilang ke `SiswaMi`/`SiswaSmp`).
- Sidebar `layouts/admin.blade.php`: tambah item baru "Validasi Data Siswa" mengikuti pola item "Tracer Alumni" yang sudah ada (icon + active-state check `request()->routeIs('validasi-data-siswa.index')`).

## Keamanan

- Endpoint publik ini tidak butuh Form Request terpisah — validasi lewat `rules()` di Livewire component, mengikuti pola `TracerAlumniForm`.
- Field yang tampil ke publik dibatasi eksplisit (`select` / pluck kolom, bukan return model penuh) — hanya `nama_lengkap` dan `tanggal_lahir`. Kolom sensitif (NIK, alamat, no telepon, nama orang tua) tidak pernah ikut serta ke Blade/Livewire publik.
- PIN gate + lockout 5x percobaan seperti dijelaskan di atas, memenuhi §5 CLAUDE.md soal rate limiting endpoint publik.
- `nama_pengisi` dan `catatan` divalidasi `required|string|max:...` untuk cegah payload berlebihan.
- Route admin pakai `role:admin` mengikuti pola seluruh route admin lain di `routes/web.php`.

## Error Handling

- PIN salah → pesan error inline, tidak membuka kelas/data apapun.
- PIN kosong di settings → pesan "belum diaktifkan admin", form PIN disembunyikan/nonaktif (bukan ditampilkan lalu selalu gagal).
- Kelas terpilih ternyata tidak punya siswa aktif (edge case: kelas dihapus semua siswanya di antara load dropdown dan submit) → tabel kosong dengan pesan "Tidak ada siswa aktif di kelas ini", tombol Lengkap/Kirim Catatan tetap bisa dipakai (kelas kosong pun valid untuk ditandai lengkap).
- Submit gagal validasi → tampilkan error field seperti pola `TracerAlumniForm`, tidak mengubah baris `validasi_data_siswas` yang sudah ada.

## Pengujian

1. Feature test: akses tanpa/dengan PIN salah tidak menampilkan data siswa.
2. Feature test: PIN benar + pilih kelas → hanya `nama_lengkap` & `tanggal_lahir` yang muncul di response (field lain seperti `nik`/`alamat` tidak ada).
3. Feature test: submit "Lengkap" membuat/mengupdate satu baris `validasi_data_siswas` dengan `status = 'lengkap'`.
4. Feature test: submit ulang kelas yang sama meng-update baris yang sama (bukan menambah baris baru) — cek `validasi_data_siswas` count tetap 1 per kombinasi.
5. Feature test: submit catatan menyimpan `status = 'ada_catatan'` dan isi `catatan`.
6. Feature test: 5x PIN salah berturut-turut mengunci percobaan berikutnya (lockout).
7. Feature test otorisasi: guest dan user role `guru` tidak bisa akses route `validasi-data-siswa` (redirect/403), hanya `admin` yang bisa.
8. Feature test: tombol "Tandai Selesai" di admin mereset status baris ke `belum`.

## Batas Lingkup

Tidak termasuk:

- Login/akun khusus wali kelas.
- PIN per kelas/per sekolah (hanya satu PIN global).
- Model `Kelas`/`Rombel` terpisah — tetap pakai `tingkat_rombel` string seperti modul lain.
- Riwayat/histori tiap submission (hanya status terkini per kelas).
- Notifikasi otomatis (email/WhatsApp) ke admin saat ada catatan baru — admin mengecek manual lewat halaman admin.
- Kolom `yayasan_id` / multi-tenancy — mengikuti kondisi codebase saat ini yang belum menerapkannya di tabel manapun.
- Update massal data siswa dari catatan (admin tetap menambahkan siswa manual lewat halaman Siswa MI/SMP yang sudah ada).
- Unduhan seluruh kelas sekaligus dari halaman publik (hanya kelas terpilih yang sudah lengkap).
- Kolom `nik`, `no_telepon`, `nomor_kip_pip` pada berkas unduhan publik — tetap hanya tersedia lewat export admin yang sudah ada.

## Keputusan dan Alasan

- PIN global (bukan per kelas) dipilih user secara eksplisit — lebih mudah didistribusikan admin, cukup untuk mencegah akses acak dari internet publik meski tidak mengisolasi antar-kelas.
- PIN disimpan plain text di `SchoolSetting`, bukan hash, karena admin harus bisa membacanya kembali untuk diberi tahu ke wali kelas secara lisan/WA — ini bukan credential login, hanya gate akses fitur berisiko rendah (nama + tanggal lahir).
- Fail-closed saat PIN belum diset — default aman, mencegah kebocoran data karena kelalaian konfigurasi.
- `updateOrCreate` per (sekolah, tingkat_rombel) dipilih di atas model riwayat karena kebutuhan utamanya adalah status terkini per kelas yang bisa dipantau admin, bukan audit trail lengkap (sudah dikonfirmasi user).
- Tidak menambah `yayasan_id` — konsistensi dengan seluruh tabel lain di codebase saat ini; menambahkannya sendirian di satu fitur baru tanpa tabel lain mengikuti justru menciptakan inkonsistensi baru, bukan mengurangi.
