<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\RoleMiddleware;

// ==========================================
// AUTH ROUTES
// ==========================================
Route::get('/login',  [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Forgot Password
Route::get('/forgot-password',  [ForgotPasswordController::class, 'showForm'])->name('password.forgot');
Route::post('/forgot-password', [ForgotPasswordController::class, 'verifyEmail'])->name('password.verify');
Route::get('/reset-password',   [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password',  [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// ==========================================
// PROTECTED ROUTES
// ==========================================
Route::middleware(AuthMiddleware::class)->group(function () {
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // API endpoint for fetching next Kode MK
    Route::get('/api/next-kode-mk', [MataKuliahController::class, 'getNextKodeMk'])->name('api.next-kode-mk');

    // Profil (mahasiswa only)
    Route::get('/profile', [MahasiswaController::class, 'profile'])->name('profile');

    // Edit Profil (all roles)
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');

    // Mahasiswa: CUD restricted to admin. Read (index/show) is for admin, dosen, staff.
    Route::middleware(RoleMiddleware::class . ':admin')->group(function () {
        Route::get('mahasiswa/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
        Route::post('mahasiswa', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
        Route::get('mahasiswa/{mahasiswa}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit');
        Route::put('mahasiswa/{mahasiswa}', [MahasiswaController::class, 'update'])->name('mahasiswa.update');
        Route::delete('mahasiswa/{mahasiswa}', [MahasiswaController::class, 'destroy'])->name('mahasiswa.destroy');
    });
    Route::get('mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
    Route::get('mahasiswa/{mahasiswa}', [MahasiswaController::class, 'show'])->name('mahasiswa.show');

    // Mata Kuliah: CUD restricted to admin. Read restricted to admin, dosen, staff.
    Route::middleware(RoleMiddleware::class . ':admin')->group(function () {
        Route::get('matakuliah/create', [MataKuliahController::class, 'create'])->name('matakuliah.create');
        Route::post('matakuliah', [MataKuliahController::class, 'store'])->name('matakuliah.store');
        Route::get('matakuliah/{matakuliah}/edit', [MataKuliahController::class, 'edit'])->name('matakuliah.edit');
        Route::put('matakuliah/{matakuliah}', [MataKuliahController::class, 'update'])->name('matakuliah.update');
        Route::delete('matakuliah/{matakuliah}', [MataKuliahController::class, 'destroy'])->name('matakuliah.destroy');
    });
    Route::get('matakuliah', [MataKuliahController::class, 'index'])->middleware(RoleMiddleware::class . ':admin,dosen,staff')->name('matakuliah.index');
    Route::get('matakuliah/{matakuliah}', [MataKuliahController::class, 'show'])->middleware(RoleMiddleware::class . ':admin,dosen,staff')->name('matakuliah.show');

    // Nilai: CUD restricted to admin/dosen. Read accessible by all (internal filter for mahasiswa).
    Route::middleware(RoleMiddleware::class . ':admin,dosen')->group(function () {
        Route::get('nilai/create', [NilaiController::class, 'create'])->name('nilai.create');
        Route::post('nilai', [NilaiController::class, 'store'])->name('nilai.store');
        Route::get('nilai/{nilai}/edit', [NilaiController::class, 'edit'])->name('nilai.edit');
        Route::put('nilai/{nilai}', [NilaiController::class, 'update'])->name('nilai.update');
        Route::delete('nilai/{nilai}', [NilaiController::class, 'destroy'])->name('nilai.destroy');
    });
    Route::get('nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('nilai/{nilai}', [NilaiController::class, 'show'])->name('nilai.show');

    // Absensi: CUD restricted to admin/dosen. Read accessible by all.
    Route::middleware(RoleMiddleware::class . ':admin,dosen')->group(function () {
        Route::get('absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
        Route::post('absensi', [AbsensiController::class, 'store'])->name('absensi.store');
        Route::get('absensi/{absensi}/edit', [AbsensiController::class, 'edit'])->name('absensi.edit');
        Route::put('absensi/{absensi}', [AbsensiController::class, 'update'])->name('absensi.update');
        Route::delete('absensi/{absensi}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
    });
    Route::get('absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('absensi/{absensi}', [AbsensiController::class, 'show'])->name('absensi.show');
});
