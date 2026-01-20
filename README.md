# 📚 Data Induk Sekolah

Aplikasi manajemen data induk sekolah berbasis web untuk mengelola data **Siswa**, **Guru**, dan **Mata Pelajaran** dengan pemisahan jenjang **MI (Madrasah Ibtidaiyah)** dan **SMP (Sekolah Menengah Pertama)**.

## ✨ Fitur Utama

### 📋 Manajemen Data Siswa

- **Data Siswa MI** - Kelola data siswa MI lengkap (NISN, NIK, nama, TTL, alamat, dll)
- **Data Siswa SMP** - Kelola data siswa SMP dengan field yang sama
- Import/Export data via Excel
- Cetak surat mutasi siswa (PDF)
- Sinkronisasi data dari API eksternal

### 👨‍🏫 Manajemen Data Guru

- **Data Guru MI** - Kelola data guru MI (NIP, NUPTK, NPK, gelar, status pegawai, dll)
- **Data Guru SMP** - Kelola data guru SMP
- Upload dokumen SK (SK Awal & SK Akhir)
- Import/Export data via Excel

### 📖 Manajemen Mata Pelajaran

- **Mapel MI** - Daftar mata pelajaran MI (PAI & Umum)
- **Mapel SMP** - Daftar mata pelajaran SMP
- **Drag & Drop Reorder** - Atur urutan mapel dengan drag & drop
- Import/Export data via Excel

### ⚙️ Fitur Lainnya

- Pengaturan profil sekolah
- Dashboard admin responsif
- Authentication dengan Laravel

---

## 🛠️ Teknologi

| Teknologi         | Versi |
| ----------------- | ----- |
| PHP               | 8.2+  |
| Laravel           | 11.x  |
| Livewire          | 4.x   |
| MySQL/SQLite      | -     |
| TailwindCSS       | 3.x   |
| Maatwebsite Excel | 3.x   |
| Barryvdh DomPDF   | 3.x   |

---

## 📦 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/eldorray/data-induk-sekolah.git
cd data-induk-sekolah
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` sesuai database Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=data_induk_sekolah
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrasi Database

```bash
php artisan migrate
```

### 6. Jalankan Aplikasi

```bash
# Terminal 1 - Laravel Server
php artisan serve

# Terminal 2 - Vite (untuk asset)
npm run dev

# Atau gunakan shortcut
composer dev
```

Akses aplikasi di: **http://127.0.0.1:8000**

---

## 📖 Cara Penggunaan

### 🔐 Login

1. Buka aplikasi di browser
2. Login menggunakan akun yang sudah terdaftar
3. Setelah login, Anda akan diarahkan ke dashboard admin

### 👨‍🎓 Mengelola Data Siswa

#### Tambah Siswa Baru

1. Pilih menu **Data Siswa MI** atau **Data Siswa SMP**
2. Klik tombol **Tambah Siswa**
3. Isi form data siswa (NISN, NIK, Nama, TTL, dll)
4. Klik **Simpan**

#### Import Data dari Excel

1. Klik tombol **Template** untuk download template Excel
2. Isi data sesuai format template
3. Klik **Import** dan pilih file Excel
4. Data akan otomatis diimport

#### Export Data ke Excel

1. Klik tombol **Export**
2. File Excel akan otomatis terdownload

#### Sinkronisasi dari API

1. Pilih sumber API (MI/SMP)
2. Klik tombol **Sync dari API**
3. Data akan disinkronkan dari server eksternal

### 👨‍🏫 Mengelola Data Guru

#### Tambah Guru Baru

1. Pilih menu **Data Guru MI** atau **Data Guru SMP**
2. Klik tombol **Tambah Guru**
3. Isi form data guru lengkap
4. Upload file SK jika diperlukan
5. Klik **Simpan**

### 📖 Mengelola Mata Pelajaran

#### Tambah Mata Pelajaran

1. Pilih menu **Mapel MI** atau **Mapel SMP**
2. Klik tombol **Tambah Mapel**
3. Isi kode, nama, kelompok (PAI/Umum), dan jam per minggu
4. Klik **Simpan**

#### Mengatur Urutan (Drag & Drop)

1. Arahkan kursor ke icon ☰ di kolom pertama
2. Klik dan tahan, lalu drag ke posisi yang diinginkan
3. Lepaskan untuk menyimpan urutan baru

### ⚙️ Pengaturan Sekolah

1. Pilih menu **Pengaturan**
2. Isi informasi sekolah (nama, NPSN, alamat, dll)
3. Klik **Simpan Pengaturan**

---

## 🔌 API Endpoints

Aplikasi menyediakan REST API untuk integrasi:

| Method | Endpoint         | Deskripsi        |
| ------ | ---------------- | ---------------- |
| GET    | `/api/siswa-mi`  | Daftar siswa MI  |
| GET    | `/api/siswa-smp` | Daftar siswa SMP |
| GET    | `/api/guru-mi`   | Daftar guru MI   |
| GET    | `/api/guru-smp`  | Daftar guru SMP  |
| GET    | `/api/mapel-mi`  | Daftar mapel MI  |
| GET    | `/api/mapel-smp` | Daftar mapel SMP |

---

## 📁 Struktur Direktori

```
app/
├── Exports/          # Export Excel classes
├── Imports/          # Import Excel classes
├── Livewire/         # Livewire components
├── Models/           # Eloquent models
└── Http/Controllers/ # API Controllers

resources/views/
├── livewire/         # Livewire blade views
├── layouts/          # Layout templates
└── pdf/              # PDF templates
```

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan buat pull request atau buka issue jika menemukan bug.

---

## 📄 Lisensi

Aplikasi ini dibuat untuk keperluan internal sekolah.

---

**Dibuat dengan ❤️ menggunakan Laravel & Livewire**
