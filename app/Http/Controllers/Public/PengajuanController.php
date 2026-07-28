<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StorePengajuanRequest;
use App\Models\ApplicationDocument;
use App\Models\InternshipApplication;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{

public function persyaratan()
{
    return view('public.persyaratan');
}

public function faq()
{
    return view('public.faq');
}

public function kontak()
{
    return view('public.kontak');
}

   public function create()
{
    $bidangs = \App\Models\Bidang::where('is_active', true)->get();
    return view('application.create', compact('bidangs'));
}

public function store(StorePengajuanRequest $request)
{
    $validated = $request->validated();

    $application = InternshipApplication::create([
        'nama' => $validated['nama'],
        'nim' => $validated['nim'],
        'universitas' => $validated['universitas'],
        'fakultas' => $validated['fakultas'],
        'program_studi' => $validated['program_studi'],
        'semester' => $validated['semester'],
        'email' => $validated['email'],
        'no_hp' => $validated['no_hp'],
        'alamat' => $validated['alamat'],
        'periode_mulai' => $validated['periode_mulai'],
        'periode_selesai' => $validated['periode_selesai'],
        'bidang_id' => $validated['bidang_id'],
        'tujuan_magang' => $validated['tujuan_magang'],
    ]);

    $suratPath = $request->file('surat_pengantar')->store('documents', 'public');
    $fotoPath = $request->file('foto')->store('documents/foto', 'public');
    $cvPath = $request->hasFile('cv') ? $request->file('cv')->store('documents', 'public') : null;
    $proposalPath = $request->hasFile('proposal') ? $request->file('proposal')->store('documents', 'public') : null;
    $portofolioPath = $request->hasFile('portofolio') ? $request->file('portofolio')->store('documents/portofolio', 'public') : null;

    ApplicationDocument::create([
        'internship_application_id' => $application->id,
        'surat_pengantar' => $suratPath,
        'foto' => $fotoPath,
        'cv' => $cvPath,
        'proposal' => $proposalPath,
        'portofolio' => $portofolioPath,
    ]);

    return redirect()
        ->route('pengajuan.success', ['application_code' => $application->application_code]);
}

    public function success(string $application_code)
    {
        $application = InternshipApplication::where('application_code', $application_code)->firstOrFail();

        return view('application.success', compact('application'));
    }

    public function checkStatusForm()
    {
        return view('application.cek-status');
    }

public function checkStatus(Request $request)
{
    $request->validate([
        'application_code' => ['required', 'string'],
        'email' => ['required', 'email'],
    ]);

    $applicationCode = trim($request->application_code);
    $email = trim($request->email);

    $application = InternshipApplication::whereRaw('UPPER(application_code) = ?', [strtoupper($applicationCode)])
        ->whereRaw('LOWER(email) = ?', [strtolower($email)])
        ->first();

    if (! $application) {
        return back()->withErrors([
            'application_code' => 'Nomor pengajuan atau email tidak ditemukan. Pastikan penulisan sudah benar, tanpa spasi tambahan.',
        ])->withInput();
    }

    return view('application.cek-status-result', compact('application'));
}
}