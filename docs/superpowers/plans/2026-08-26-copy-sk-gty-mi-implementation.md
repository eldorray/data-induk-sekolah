# Copy SK GTY MI Implementation Plan

> **For Hermes:** Use subagent-driven-development skill to implement this plan task-by-task.

**Goal:** Menambahkan aksi Copy SK yang membuka form pembuatan SK baru dengan field template tersalin, sementara guru dan jabatan penerima wajib dipilih ulang.

**Architecture:** Tambahkan state copy eksplisit serta metode `openCopyModal()` pada komponen Livewire existing. Gunakan helper pengisian field template agar mode edit/copy jelas, lalu tambahkan tombol aksi dan variasi judul modal pada Blade. Test Livewire membuktikan isolasi state, create-vs-update, dan integritas sumber.

**Tech Stack:** Laravel, Livewire, Blade, PHPUnit/Pest existing project test runner.

---

### Task 1: Tambahkan test state Copy SK

**Objective:** Membuktikan kontrak form copy sebelum implementasi.

**Files:**
- Create: `tests/Feature/Livewire/SkGtyMiCopyTest.php`
- Inspect factories/fixtures for `SkGtyMi`, `GuruMi`, and school settings.

**Steps:**
1. Buat source SK lengkap dan guru sumber.
2. Panggil `openCopyModal(sourceId)` via `Livewire::test()`.
3. Assert method belum ada atau state copy belum tersedia (RED).
4. Tambahkan assertions untuk template fields, nomor baru, kosongnya guru/identitas/jabatan, status draft, dan modal terbuka.

**Verification:** Jalankan test file terfokus, expected FAIL sebelum implementasi.

### Task 2: Implementasikan state dan openCopyModal

**Objective:** Mengisi form salinan tanpa mengubah sumber atau membuat record.

**Files:**
- Modify: `app/Livewire/SkGtyMiManagement.php`
- Test: `tests/Feature/Livewire/SkGtyMiCopyTest.php`

**Steps:**
1. Tambahkan `isCopying` dan `copiedFromNomorSk`.
2. Implementasikan `openCopyModal(int $id)` menggunakan `findOrFail()` tanpa ketergantungan relasi guru sumber.
3. Reset form, salin hanya whitelist field template, buat nomor baru, kosongkan penerima/jabatan, paksa draft.
4. Pastikan `isEditing=false`, modal terbuka, dan tidak ada persistensi.
5. Reset state copy di `resetForm()`.
6. Jalankan test state hingga PASS.

### Task 3: Verifikasi pemilihan guru dan penyimpanan

**Objective:** Membuktikan copy menjadi SK baru untuk guru baru dan sumber tetap identik.

**Files:**
- Modify: `tests/Feature/Livewire/SkGtyMiCopyTest.php`
- Modify if needed: `app/Livewire/SkGtyMiManagement.php`

**Steps:**
1. Pilih guru kedua lewat `selectGuru()`.
2. Assert identitas berubah ke guru kedua dan jabatan tetap kosong.
3. Isi jabatan lalu panggil `save()`.
4. Assert jumlah record bertambah satu, salinan memakai nomor/guru baru dan status draft.
5. Assert source attributes dan `updated_at` tidak berubah.
6. Assert menutup modal tanpa save tidak menambah record.

### Task 4: Tambahkan UI tombol dan mode modal

**Objective:** Menyediakan affordance copy yang jelas dan aksesibel.

**Files:**
- Modify: `resources/views/livewire/sk-gty...ment.blade.php`
- Test: `tests/Feature/Livewire/SkGtyMiCopyTest.php` or Blade string assertion appropriate to project.

**Steps:**
1. Tambahkan tombol `wire:click="openCopyModal(id)"` pada kolom Aksi.
2. Tambahkan icon duplicate, title, dan aria-label dengan nomor sumber.
3. Ubah judul/subteks modal berdasarkan Create/Edit/Copy.
4. Ubah label submit mode copy menjadi `Simpan sebagai SK Baru`.
5. Pastikan aksi existing Cetak/Edit/Hapus tidak berubah.

### Task 5: Verifikasi regresi dan kualitas

**Objective:** Membuktikan fitur aman untuk suite project.

**Steps:**
1. Jalankan test Copy SK terfokus.
2. Jalankan seluruh test suite yang relevan.
3. Jalankan Pint pada file berubah atau `vendor/bin/pint --test` sesuai baseline.
4. Jalankan `php artisan view:cache` untuk kompilasi Blade.
5. Jalankan `git diff --check` dan inspeksi diff.
6. Lakukan review independen spec compliance dan code quality.
7. Commit implementasi setelah semua gate PASS.

## Non-goals

Tidak membuat template table baru, copy massal, copy lintas jenis SK, perubahan PDF, atau perubahan generator nomor.