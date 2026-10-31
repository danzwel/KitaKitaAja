<?php

namespace App\Http\Middleware;

use App\Models\Intern;
use App\Services\InternshipStatusService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInternshipIsActive
{
    /**
     * Prevent attendance activity after the participant's internship period ends.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Intern|null $intern */
        $intern = $request->user('intern');

        if (! $intern) {
            return redirect()->route('intern.login');
        }

        $intern->loadMissing('internshipApplication');
        app(InternshipStatusService::class)->syncFor($intern);

        if ($intern->fresh()->status !== Intern::STATUS_AKTIF) {
            return redirect()->route('intern.dashboard')->with('error', 'Masa magang Anda sudah selesai. Absensi serta pengajuan izin/sakit tidak lagi tersedia.');
        }

        return $next($request);
    }
}
