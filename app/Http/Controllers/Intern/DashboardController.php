<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $intern = auth('intern')->user();

        return view('intern.dashboard', [
            'attendanceStats' => [
                'hadir' => $intern->attendanceRecords()->whereIn('check_in_status', ['hadir', 'tepat_waktu', 'terlambat'])->count(),
                'izin' => $intern->approvedLeaveDays('izin'),
                'sakit' => $intern->approvedLeaveDays('sakit'),
                'alpa' => $intern->alphaDays(),
            ],
        ]);
    }
}
