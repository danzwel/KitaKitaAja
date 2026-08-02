<?php

use App\Http\Controllers\Public\LandingController;
use App\Http\Controllers\Public\PengajuanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Intern\Auth\AuthenticatedSessionController as InternAuthenticatedSessionController;
use App\Http\Controllers\Intern\DashboardController as InternDashboardController;
use App\Http\Controllers\Intern\ForceChangePasswordController;
use App\Http\Controllers\Intern\ProfileController as InternProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::get('/persyaratan', [PengajuanController::class, 'persyaratan'])->name('persyaratan');

Route::get('/faq', [PengajuanController::class, 'faq'])->name('faq');
Route::get('/kontak', [PengajuanController::class, 'kontak'])->name('kontak');

Route::get('/pengajuan', [PengajuanController::class, 'create'])->name('pengajuan.create');
Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
Route::get('/pengajuan/sukses/{application_code}', [PengajuanController::class, 'success'])->name('pengajuan.success');

Route::get('/cek-status', [PengajuanController::class, 'checkStatusForm'])->name('cek-status');
Route::post('/cek-status', [PengajuanController::class, 'checkStatus'])->name('cek-status.result');

Route::middleware('guest:intern')->group(function () {
    Route::get('/mahasiswa/login', [InternAuthenticatedSessionController::class, 'create'])->name('intern.login');
    Route::post('/mahasiswa/login', [InternAuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth:intern')->group(function () {
    // Rute yang hanya bisa diakses setelah ganti password
    Route::middleware('intern.password.change')->group(function () {
        Route::get('/mahasiswa/dashboard', InternDashboardController::class)->name('intern.dashboard');
        
        Route::get('/mahasiswa/profil', [InternProfileController::class, 'edit'])->name('intern.profile.edit');
        Route::patch('/mahasiswa/profil', [InternProfileController::class, 'update'])->name('intern.profile.update');
        Route::put('/mahasiswa/profil/password', [InternProfileController::class, 'updatePassword'])->name('intern.profile.password');
        Route::post('/mahasiswa/profil/foto', [InternProfileController::class, 'uploadPhoto'])->name('intern.profile.photo');
    });

    // Rute Force Change Password
    Route::get('/mahasiswa/ganti-password', [ForceChangePasswordController::class, 'create'])->name('intern.password.change');
    Route::post('/mahasiswa/ganti-password', [ForceChangePasswordController::class, 'store']);

    Route::post('/mahasiswa/logout', [InternAuthenticatedSessionController::class, 'destroy'])->name('intern.logout');
});

Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
