<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\SiswaMiManagement;
use App\Livewire\SiswaSmpManagement;
use App\Livewire\GuruMiManagement;
use App\Livewire\GuruSmpManagement;
use App\Livewire\MapelMiManagement;
use App\Livewire\MapelSmpManagement;
use App\Livewire\MutasiSiswaManagement;
use App\Livewire\SuratKeteranganAktifManagement;
use App\Livewire\SkGtyMiManagement;
use App\Livewire\SkTugasTambahanMiManagement;
use App\Livewire\SkPembagianTugasMiManagement;
use App\Livewire\SuratPernyataanInsentifManagement;
use App\Livewire\SuratPernyataanTangcerManagement;
use App\Livewire\SuratRekapPkhManagement;
use App\Livewire\LicenseManagement;
use App\Livewire\UserManagement;
use App\Livewire\TracerAlumniForm;
use App\Livewire\TracerAlumniManagement;
use App\Livewire\NilaiIjazahKelas6\Index as NilaiIjazahIndex;
use App\Livewire\NilaiIjazahKelas6\Show as NilaiIjazahShow;
use App\Http\Controllers\MutasiSiswaController;
use App\Http\Controllers\NilaiIjazahController;
use App\Http\Controllers\SuratKeteranganAktifController;
use App\Http\Controllers\SkGuruMiController;
use App\Http\Controllers\SuratPernyataanInsentifController;
use App\Http\Controllers\SuratPernyataanTangcerController;
use App\Http\Controllers\SuratRekapPkhController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Public Tracer Alumni Form
Route::get('tracer-alumni', TracerAlumniForm::class)->name('tracer-alumni.form');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Siswa MI & SMP Routes
Route::get('siswa-mi', SiswaMiManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('siswa-mi.index');

Route::get('siswa-smp', SiswaSmpManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('siswa-smp.index');

// Guru MI & SMP Routes
Route::get('guru-mi', GuruMiManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('guru-mi.index');

Route::get('guru-smp', GuruSmpManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('guru-smp.index');

// Mapel MI & SMP Routes
Route::get('mapel-mi', MapelMiManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('mapel-mi.index');

Route::get('mapel-smp', MapelSmpManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('mapel-smp.index');

Route::get('mutasi', MutasiSiswaManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('mutasi.index');

Route::get('mutasi/{id}/print', [MutasiSiswaController::class, 'printPdf'])
    ->middleware(['auth', 'role:admin'])
    ->name('mutasi.print');

Route::get('surat-aktif', SuratKeteranganAktifManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('surat-aktif.index');

Route::get('surat-aktif/{id}/print', [SuratKeteranganAktifController::class, 'printPdf'])
    ->middleware(['auth', 'role:admin'])
    ->name('surat-aktif.print');

// Surat Rekap PKH
Route::get('surat-rekap-pkh', SuratRekapPkhManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('surat-rekap-pkh.index');

Route::get('surat-rekap-pkh/{id}/print', [SuratRekapPkhController::class, 'printPdf'])
    ->middleware(['auth', 'role:admin'])
    ->name('surat-rekap-pkh.print');

Route::get('settings', \App\Livewire\SchoolSettingsManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('settings.index');

// SK Guru MI Routes
Route::get('sk-gty-mi', SkGtyMiManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('sk-gty-mi.index');

Route::get('sk-gty-mi/{id}/print', [SkGuruMiController::class, 'printSkGty'])
    ->middleware(['auth', 'role:admin'])
    ->name('sk-gty-mi.print');

Route::get('sk-tugas-tambahan-mi', SkTugasTambahanMiManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('sk-tugas-tambahan-mi.index');

Route::get('sk-tugas-tambahan-mi/{id}/print', [SkGuruMiController::class, 'printSkTugasTambahan'])
    ->middleware(['auth', 'role:admin'])
    ->name('sk-tugas-tambahan-mi.print');

Route::get('sk-pembagian-tugas-mi', SkPembagianTugasMiManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('sk-pembagian-tugas-mi.index');

Route::get('sk-pembagian-tugas-mi/{id}/print', [SkGuruMiController::class, 'printSkPembagianTugas'])
    ->middleware(['auth', 'role:admin'])
    ->name('sk-pembagian-tugas-mi.print');

// Surat Pernyataan Insentif
Route::get('surat-pernyataan-insentif', SuratPernyataanInsentifManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('surat-pernyataan-insentif.index');

Route::get('surat-pernyataan-insentif/{id}/print', [SuratPernyataanInsentifController::class, 'printPdf'])
    ->middleware(['auth', 'role:admin'])
    ->name('surat-pernyataan-insentif.print');

Route::get('surat-pernyataan-insentif-export-all', [SuratPernyataanInsentifController::class, 'exportAllPdf'])
    ->middleware(['auth', 'role:admin'])
    ->name('surat-pernyataan-insentif.export-all');

// Surat Pernyataan Tangcer
Route::get('surat-pernyataan-tangcer', SuratPernyataanTangcerManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('surat-pernyataan-tangcer.index');

Route::get('surat-pernyataan-tangcer/{id}/print', [SuratPernyataanTangcerController::class, 'printPdf'])
    ->middleware(['auth', 'role:admin'])
    ->name('surat-pernyataan-tangcer.print');

Route::get('surat-pernyataan-tangcer-export-all', [SuratPernyataanTangcerController::class, 'exportAllPdf'])
    ->middleware(['auth', 'role:admin'])
    ->name('surat-pernyataan-tangcer.export-all');

// License Management
Route::get('licenses', LicenseManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('licenses.index');

// User Management (admin only)
Route::get('users', UserManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('users.index');

// Tracer Alumni Management (admin only)
Route::get('tracer-alumni-management', TracerAlumniManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('tracer-alumni.index');

// Nilai Ijazah Kelas 6 (admin + guru)
Route::middleware(['auth', 'role:admin,guru'])->group(function () {
    Route::get('nilai-ijazah-kelas-6', NilaiIjazahIndex::class)
        ->name('nilai-ijazah.index');

    Route::get('nilai-ijazah-kelas-6/{tahunAjaran}', NilaiIjazahShow::class)
        ->whereNumber('tahunAjaran')
        ->name('nilai-ijazah.show');

    Route::get('nilai-ijazah-kelas-6/{tahunAjaran}/cetak-cover', [NilaiIjazahController::class, 'printCover'])
        ->whereNumber('tahunAjaran')
        ->name('nilai-ijazah.print-cover');

    Route::get('nilai-ijazah-kelas-6/{tahunAjaran}/cetak-nilai', [NilaiIjazahController::class, 'printNilai'])
        ->whereNumber('tahunAjaran')
        ->name('nilai-ijazah.print-nilai');

    Route::get('nilai-ijazah-kelas-6/{tahunAjaran}/export-rekap', [NilaiIjazahController::class, 'exportRekap'])
        ->whereNumber('tahunAjaran')
        ->name('nilai-ijazah.export-rekap');

    Route::get('nilai-ijazah-kelas-6/{tahunAjaran}/print-rekap', [NilaiIjazahController::class, 'printRekap'])
        ->whereNumber('tahunAjaran')
        ->name('nilai-ijazah.print-rekap');
});


// Auth Routes (Register disabled)
Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', function () {
        \Illuminate\Support\Facades\Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
