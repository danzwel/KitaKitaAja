<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use Illuminate\View\View;

class InternshipReportController extends Controller
{
    public function index(): View
    {
        Intern::query()
            ->whereNotNull('final_report_path')
            ->whereNull('final_report_viewed_at')
            ->update(['final_report_viewed_at' => now()]);

        return view('admin.reports.index', [
            'reports' => Intern::query()
                ->with(['department', 'internshipApplication.bidang'])
                ->whereNotNull('final_report_path')
                ->orderByDesc('final_report_uploaded_at')
                ->get(),
        ]);
    }
}
