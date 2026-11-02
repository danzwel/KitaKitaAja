<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use App\Models\InternshipCertificate;
use App\Services\InternshipCertificateService;
use Illuminate\Http\RedirectResponse;

class InternshipCertificateController extends Controller
{
    public function issue(Intern $intern, InternshipCertificateService $certificates): RedirectResponse
    {
        $this->authorize('issueCertificate', $intern);

        $certificates->issue($intern, request()->user('admin'));

        return back()->with('success', 'Sertifikat magang berhasil dikirim ke portal peserta.');
    }

    public function download(InternshipCertificate $certificate)
    {
        $this->authorize('view', $certificate->intern);

        return $this->pdfResponse($certificate);
    }

    private function pdfResponse(InternshipCertificate $certificate)
    {
        $certificate->loadMissing('intern.internshipApplication.bidang', 'intern.department');
        $intern = $certificate->intern;
        $application = $intern->internshipApplication;
        $start = $application?->periode_mulai;
        $end = $application?->periode_selesai;
        $department = $application?->bidang?->nama_bidang ?? $intern->department?->name ?? '-';

        return app('dompdf.wrapper')
            ->setPaper('a4', 'landscape')
            ->loadView('certificates.pdf', compact('certificate', 'intern', 'start', 'end', 'department'))
            ->download('sertifikat-magang-'.$intern->username.'.pdf');
    }
}
