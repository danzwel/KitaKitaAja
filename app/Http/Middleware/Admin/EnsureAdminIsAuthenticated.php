<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware role Admin.
 * Memastikan hanya user yang login lewat guard 'admin' yang bisa
 * mengakses halaman modul Admin. Terpisah dari middleware auth mahasiswa
 * (modul Raihan) supaya tidak saling tumpang tindih.
 */
class EnsureAdminIsAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Pastikan Gate/Policy Laravel memakai user dari guard Admin,
        // bukan guard web default. Tanpa ini authorize() akan menerima
        // user null dan seluruh halaman Admin berakhir 403.
        Auth::shouldUse('admin');

        return $next($request);
    }
}
