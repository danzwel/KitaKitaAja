<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Intern;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_applications' => Application::count(),
            'pending' => Application::status(Application::STATUS_MENUNGGU)->count(),
            'processed' => Application::status(Application::STATUS_DIPROSES)->count(),
            'accepted' => Application::status(Application::STATUS_DITERIMA)->count(),
            'rejected' => Application::status(Application::STATUS_DITOLAK)->count(),
            'active_interns' => Intern::status(Intern::STATUS_AKTIF)->count(),
        ];

        // Grafik jumlah pengajuan per bulan (12 bulan terakhir)
        $monthlyApplications = Application::query()
            ->select(DB::raw('MONTH(application_date) as month'), DB::raw('COUNT(*) as total'))
            ->whereYear('application_date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $chartData = collect(range(1, 12))->map(fn ($month) => $monthlyApplications->get($month, 0));

        $latestApplications = Application::with('department')
            ->latest('application_date')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'chartData', 'latestApplications'));
    }
}
