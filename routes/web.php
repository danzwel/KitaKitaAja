<?php

use App\Http\Controllers\Public\LandingController;
use App\Http\Controllers\Public\PengajuanController;
use App\Http\Controllers\ProfileController;
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
