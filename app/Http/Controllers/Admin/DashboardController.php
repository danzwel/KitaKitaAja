<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Intern;
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

        // Grafik jumlah pengajuan per bulan dalam 12 bulan terakhir.
        $periodStart = now()->startOfMonth()->subMonths(11);
        $periodEnd = now()->endOfMonth();
        $monthlyApplications = Application::query()
            ->whereBetween('application_date', [$periodStart, $periodEnd])
            ->get(['application_date'])
            ->groupBy(fn (Application $application) => $application->application_date->format('Y-m'))
            ->map->count();

        $chartData = collect(range(0, 11))->map(
            fn (int $offset) => $monthlyApplications->get($periodStart->copy()->addMonths($offset)->format('Y-m'), 0)
        );

        $latestApplications = Application::with('department')
            ->latest('application_date')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'chartData', 'latestApplications'));
    }
}
