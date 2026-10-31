<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $intern = auth('intern')->user();
        $isCompleted = $intern->status === \App\Models\Intern::STATUS_SELESAI;
        // working_minutes adalah atribut hasil hitung dari waktu datang/pulang,
        // bukan kolom fisik di database.
        $workingMinutes = $intern->attendanceRecords()
            ->get()
            ->sum(fn (\App\Models\AttendanceRecord $record): int => $record->working_minutes);

        return view($isCompleted ? 'intern.dashboard-completed' : 'intern.dashboard', [
            'intern' => $intern,
            'workingDuration' => $this->workingDuration($workingMinutes),
            'attendanceStats' => [
                'hadir' => $intern->attendanceRecords()->whereIn('check_in_status', ['hadir', 'tepat_waktu', 'terlambat'])->count(),
                'izin' => $intern->approvedLeaveDays('izin'),
                'sakit' => $intern->approvedLeaveDays('sakit'),
                'alpa' => $intern->alphaDays(),
            ],
        ]);
    }

    private function workingDuration(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        return $remainingMinutes > 0 ? $hours.' jam '.$remainingMinutes.' menit' : $hours.' jam';
    }
}
