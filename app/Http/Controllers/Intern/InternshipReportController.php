<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InternshipReportController extends Controller
{
    public function index(Request $request): View
    {
        return view('intern.reports.index', ['intern' => $request->user('intern')]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'final_report' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        $intern = $request->user('intern');
        if ($intern->final_report_path) {
            Storage::disk('public')->delete($intern->final_report_path);
        }

        $path = $request->file('final_report')->store('internship-final-reports', 'public');
        $intern->update([
            'final_report_path' => $path,
            'final_report_uploaded_at' => now(),
            'final_report_viewed_at' => null,
        ]);

        return redirect()->route('intern.reports.index')->with('status', 'Laporan hasil magang berhasil diunggah.');
    }
}
