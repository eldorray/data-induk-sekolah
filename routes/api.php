<?php

use App\Http\Controllers\Api\SiswaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Siswa API Routes (public for sync with external apps)
Route::prefix('siswa')->group(function () {
    Route::get('/', [SiswaController::class, 'index'])->name('api.siswa.index');
    Route::get('/all', [SiswaController::class, 'all'])->name('api.siswa.all');
    Route::post('/', [SiswaController::class, 'store'])->name('api.siswa.store');
    Route::get('/{siswa}', [SiswaController::class, 'show'])->name('api.siswa.show');
    Route::put('/{siswa}', [SiswaController::class, 'update'])->name('api.siswa.update');
    Route::delete('/{siswa}', [SiswaController::class, 'destroy'])->name('api.siswa.destroy');
    Route::post('/sync', [SiswaController::class, 'sync'])->name('api.siswa.sync');
});

// Guru API Routes
Route::prefix('guru')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\GuruController::class, 'index'])->name('api.guru.index');
    Route::get('/all', [\App\Http\Controllers\Api\GuruController::class, 'all'])->name('api.guru.all');
    Route::post('/', [\App\Http\Controllers\Api\GuruController::class, 'store'])->name('api.guru.store');
    Route::get('/{guru}', [\App\Http\Controllers\Api\GuruController::class, 'show'])->name('api.guru.show');
    Route::put('/{guru}', [\App\Http\Controllers\Api\GuruController::class, 'update'])->name('api.guru.update');
    Route::delete('/{guru}', [\App\Http\Controllers\Api\GuruController::class, 'destroy'])->name('api.guru.destroy');
    Route::post('/sync', [\App\Http\Controllers\Api\GuruController::class, 'sync'])->name('api.guru.sync');
});

// Siswa MI API Routes
Route::prefix('siswa-mi')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\SiswaMiController::class, 'index'])->name('api.siswa-mi.index');
    Route::get('/all', [\App\Http\Controllers\Api\SiswaMiController::class, 'all'])->name('api.siswa-mi.all');
    Route::post('/', [\App\Http\Controllers\Api\SiswaMiController::class, 'store'])->name('api.siswa-mi.store');
    Route::get('/{siswaMi}', [\App\Http\Controllers\Api\SiswaMiController::class, 'show'])->name('api.siswa-mi.show');
    Route::put('/{siswaMi}', [\App\Http\Controllers\Api\SiswaMiController::class, 'update'])->name('api.siswa-mi.update');
    Route::delete('/{siswaMi}', [\App\Http\Controllers\Api\SiswaMiController::class, 'destroy'])->name('api.siswa-mi.destroy');
    Route::post('/sync', [\App\Http\Controllers\Api\SiswaMiController::class, 'sync'])->name('api.siswa-mi.sync');
});

// Siswa SMP API Routes
Route::prefix('siswa-smp')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\SiswaSmpController::class, 'index'])->name('api.siswa-smp.index');
    Route::get('/all', [\App\Http\Controllers\Api\SiswaSmpController::class, 'all'])->name('api.siswa-smp.all');
    Route::post('/', [\App\Http\Controllers\Api\SiswaSmpController::class, 'store'])->name('api.siswa-smp.store');
    Route::get('/{siswaSmp}', [\App\Http\Controllers\Api\SiswaSmpController::class, 'show'])->name('api.siswa-smp.show');
    Route::put('/{siswaSmp}', [\App\Http\Controllers\Api\SiswaSmpController::class, 'update'])->name('api.siswa-smp.update');
    Route::delete('/{siswaSmp}', [\App\Http\Controllers\Api\SiswaSmpController::class, 'destroy'])->name('api.siswa-smp.destroy');
    Route::post('/sync', [\App\Http\Controllers\Api\SiswaSmpController::class, 'sync'])->name('api.siswa-smp.sync');
});

// Guru MI API Routes
Route::prefix('guru-mi')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\GuruMiController::class, 'index'])->name('api.guru-mi.index');
    Route::get('/all', [\App\Http\Controllers\Api\GuruMiController::class, 'all'])->name('api.guru-mi.all');
    Route::post('/', [\App\Http\Controllers\Api\GuruMiController::class, 'store'])->name('api.guru-mi.store');
    Route::get('/{guruMi}', [\App\Http\Controllers\Api\GuruMiController::class, 'show'])->name('api.guru-mi.show');
    Route::put('/{guruMi}', [\App\Http\Controllers\Api\GuruMiController::class, 'update'])->name('api.guru-mi.update');
    Route::delete('/{guruMi}', [\App\Http\Controllers\Api\GuruMiController::class, 'destroy'])->name('api.guru-mi.destroy');
    Route::post('/sync', [\App\Http\Controllers\Api\GuruMiController::class, 'sync'])->name('api.guru-mi.sync');
});

// Guru SMP API Routes
Route::prefix('guru-smp')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\GuruSmpController::class, 'index'])->name('api.guru-smp.index');
    Route::get('/all', [\App\Http\Controllers\Api\GuruSmpController::class, 'all'])->name('api.guru-smp.all');
    Route::post('/', [\App\Http\Controllers\Api\GuruSmpController::class, 'store'])->name('api.guru-smp.store');
    Route::get('/{guruSmp}', [\App\Http\Controllers\Api\GuruSmpController::class, 'show'])->name('api.guru-smp.show');
    Route::put('/{guruSmp}', [\App\Http\Controllers\Api\GuruSmpController::class, 'update'])->name('api.guru-smp.update');
    Route::delete('/{guruSmp}', [\App\Http\Controllers\Api\GuruSmpController::class, 'destroy'])->name('api.guru-smp.destroy');
    Route::post('/sync', [\App\Http\Controllers\Api\GuruSmpController::class, 'sync'])->name('api.guru-smp.sync');
});

// Mapel MI API Routes
Route::prefix('mapel-mi')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\MapelMiController::class, 'index'])->name('api.mapel-mi.index');
    Route::get('/all', [\App\Http\Controllers\Api\MapelMiController::class, 'all'])->name('api.mapel-mi.all');
    Route::post('/', [\App\Http\Controllers\Api\MapelMiController::class, 'store'])->name('api.mapel-mi.store');
    Route::get('/{mapelMi}', [\App\Http\Controllers\Api\MapelMiController::class, 'show'])->name('api.mapel-mi.show');
    Route::put('/{mapelMi}', [\App\Http\Controllers\Api\MapelMiController::class, 'update'])->name('api.mapel-mi.update');
    Route::delete('/{mapelMi}', [\App\Http\Controllers\Api\MapelMiController::class, 'destroy'])->name('api.mapel-mi.destroy');
    Route::post('/sync', [\App\Http\Controllers\Api\MapelMiController::class, 'sync'])->name('api.mapel-mi.sync');
});

// Mapel SMP API Routes
Route::prefix('mapel-smp')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\MapelSmpController::class, 'index'])->name('api.mapel-smp.index');
    Route::get('/all', [\App\Http\Controllers\Api\MapelSmpController::class, 'all'])->name('api.mapel-smp.all');
    Route::post('/', [\App\Http\Controllers\Api\MapelSmpController::class, 'store'])->name('api.mapel-smp.store');
    Route::get('/{mapelSmp}', [\App\Http\Controllers\Api\MapelSmpController::class, 'show'])->name('api.mapel-smp.show');
    Route::put('/{mapelSmp}', [\App\Http\Controllers\Api\MapelSmpController::class, 'update'])->name('api.mapel-smp.update');
    Route::delete('/{mapelSmp}', [\App\Http\Controllers\Api\MapelSmpController::class, 'destroy'])->name('api.mapel-smp.destroy');
    Route::post('/sync', [\App\Http\Controllers\Api\MapelSmpController::class, 'sync'])->name('api.mapel-smp.sync');
});

// License Verification API (for e-raport clients)
Route::post('/license/verify', [\App\Http\Controllers\Api\LicenseVerifyController::class, 'verify'])->name('api.license.verify');

