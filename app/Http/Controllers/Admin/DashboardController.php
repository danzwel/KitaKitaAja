<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternshipApplication;
use App\Models\Intern;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $pendingStatus = 'menunggu_verifikasi';
        $processedStatus = 'diproses';
        $acceptedStatus = 'diterima';
        $rejectedStatus = 'ditolak';

        $stats = [
            'total_applications' => InternshipApplication::count(),
            'pending' => InternshipApplication::where('status', $pendingStatus)->count(),
            'processed' => InternshipApplication::where('status', $processedStatus)->count(),
            'accepted' => InternshipApplication::where('status', $acceptedStatus)->count(),
            'rejected' => InternshipApplication::where('status', $rejectedStatus)->count(),
            'active_interns' => class_exists(Intern::class)
                ? Intern::where('status', 'aktif')->count()
                : 0,
        ];

        // Grafik jumlah pengajuan per bulan dalam 12 bulan terakhir.
        $periodStart = now()->startOfMonth()->subMonths(11);
        $periodEnd = now()->endOfMonth();
        $monthlyApplications = InternshipApplication::query()
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->get(['created_at'])
            ->groupBy(fn (InternshipApplication $application) => $application->created_at->format('Y-m'))
            ->map->count();

        $chartLabels = collect(range(0, 11))->map(
            fn (int $offset) => $periodStart->copy()->addMonths($offset)->translatedFormat('M Y')
        );
        $chartData = collect(range(0, 11))->map(
            fn (int $offset) => $monthlyApplications->get($periodStart->copy()->addMonths($offset)->format('Y-m'), 0)
        );

        $latestApplications = InternshipApplication::with('bidang')
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'chartLabels',
            'chartData',
            'latestApplications',
        ));
    }
}
