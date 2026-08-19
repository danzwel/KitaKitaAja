<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $intern = auth('intern')->user();

        return view('intern.dashboard', [
            'attendanceStats' => [
                'hadir' => $intern->attendanceRecords()->whereIn('check_in_status', ['hadir', 'tepat_waktu'])->count(),
                'izin' => $intern->leaveRequests()->where('type', 'izin')->where('status', 'approved')->count(),
                'sakit' => $intern->leaveRequests()->where('type', 'sakit')->where('status', 'approved')->count(),
                'terlambat' => $intern->attendanceRecords()->where('check_in_status', AttendanceRecord::STATUS_LATE)->count(),
            ],
        ]);
    }
}
