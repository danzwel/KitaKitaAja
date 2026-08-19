<?php

use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\InternController;
use App\Http\Controllers\Admin\ReplyLetterController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AttendanceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Semua route modul Admin (Daniel). File ini dipisah dari web.php supaya
| tidak bentrok dengan route Landing Page (Sofi) / Dashboard Mahasiswa
| (Raihan). Cara pasang: lihat instruksi di bawah response ini.
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // ---------- Guest (belum login) ----------
    Route::middleware('admin.guest')->group(function () {
        Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AdminAuthenticatedSessionController::class, 'store'])
            ->name('login.authenticate');
    });

    // ---------- Protected (harus login sebagai admin) ----------
    Route::middleware('admin.auth')->group(function () {
        Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('attendance/recap', [AttendanceController::class, 'recap'])->name('attendance.recap');
        Route::post('attendance/sessions', [AttendanceController::class, 'storeSession'])->name('attendance.sessions.store');
        Route::patch('attendance/sessions/{session}/close', [AttendanceController::class, 'closeSession'])->name('attendance.sessions.close');
        Route::patch('attendance/records/{record}/review', [AttendanceController::class, 'reviewAttendance'])->name('attendance.records.review');
        Route::patch('attendance/leave/{leaveRequest}/review', [AttendanceController::class, 'reviewLeave'])->name('attendance.leave.review');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Kelola Pengajuan + Approval
        Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::patch('applications/{application}/approve', [ApplicationController::class, 'approve'])->name('applications.approve');
        Route::patch('applications/{application}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');

        // Kelola Mahasiswa Magang
        Route::get('interns', [InternController::class, 'index'])->name('interns.index');
        Route::get('interns/{intern}', [InternController::class, 'show'])->name('interns.show');
        Route::get('interns/{intern}/edit', [InternController::class, 'edit'])->name('interns.edit');
        Route::put('interns/{intern}', [InternController::class, 'update'])->name('interns.update');
        Route::delete('interns/{intern}', [InternController::class, 'destroy'])->name('interns.destroy');
        Route::patch('interns/{intern}/reset-password', [InternController::class, 'resetPassword'])->name('interns.reset-password');

        // Upload Surat Balasan
        Route::post('interns/{intern}/reply-letters', [ReplyLetterController::class, 'store'])->name('reply-letters.store');
        Route::delete('reply-letters/{replyLetter}', [ReplyLetterController::class, 'destroy'])->name('reply-letters.destroy');

        // CRUD Bidang Magang
        Route::resource('departments', DepartmentController::class)->except(['show']);
    });
});
