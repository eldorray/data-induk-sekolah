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
use App\Http\Controllers\MutasiSiswaController;
use App\Http\Controllers\SuratKeteranganAktifController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Siswa MI & SMP Routes
Route::get('siswa-mi', SiswaMiManagement::class)
    ->middleware(['auth'])
    ->name('siswa-mi.index');

Route::get('siswa-smp', SiswaSmpManagement::class)
    ->middleware(['auth'])
    ->name('siswa-smp.index');

// Guru MI & SMP Routes
Route::get('guru-mi', GuruMiManagement::class)
    ->middleware(['auth'])
    ->name('guru-mi.index');

Route::get('guru-smp', GuruSmpManagement::class)
    ->middleware(['auth'])
    ->name('guru-smp.index');

// Mapel MI & SMP Routes
Route::get('mapel-mi', MapelMiManagement::class)
    ->middleware(['auth'])
    ->name('mapel-mi.index');

Route::get('mapel-smp', MapelSmpManagement::class)
    ->middleware(['auth'])
    ->name('mapel-smp.index');

Route::get('mutasi', MutasiSiswaManagement::class)
    ->middleware(['auth'])
    ->name('mutasi.index');

Route::get('mutasi/{id}/print', [MutasiSiswaController::class, 'printPdf'])
    ->middleware(['auth'])
    ->name('mutasi.print');

Route::get('surat-aktif', SuratKeteranganAktifManagement::class)
    ->middleware(['auth'])
    ->name('surat-aktif.index');

Route::get('surat-aktif/{id}/print', [SuratKeteranganAktifController::class, 'printPdf'])
    ->middleware(['auth'])
    ->name('surat-aktif.print');

Route::get('settings', \App\Livewire\SchoolSettingsManagement::class)
    ->middleware(['auth'])
    ->name('settings.index');


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
