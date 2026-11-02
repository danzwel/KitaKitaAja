<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\InternshipCertificate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InternshipCertificateController extends Controller
{
    public function index(Request $request): View
    {
        return view('intern.certificates.index', [
            'certificate' => $request->user('intern')->certificate,
        ]);
    }

    public function download(Request $request, InternshipCertificate $certificate)
    {
        abort_unless($certificate->intern_id === $request->user('intern')->id, 403);

        return app('dompdf.wrapper')
            ->setPaper('a4', 'landscape')
            ->loadView('certificates.pdf', $this->certificateData($certificate))
            ->download('sertifikat-magang-'.$request->user('intern')->username.'.pdf');
    }

    private function certificateData(InternshipCertificate $certificate): array
    {
        $certificate->loadMissing('intern.internshipApplication.bidang', 'intern.department');
        $intern = $certificate->intern;
        $application = $intern->internshipApplication;

        return [
            'certificate' => $certificate,
            'intern' => $intern,
            'start' => $application?->periode_mulai,
            'end' => $application?->periode_selesai,
            'department' => $application?->bidang?->nama_bidang ?? $intern->department?->name ?? '-',
        ];
    }
}
