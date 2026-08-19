<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(private readonly AttendanceService $attendanceService) {}

    public function index(Request $request): View
    {
        $intern = $request->user('intern');
        $today = now()->toDateString();

        return view('intern.attendance.index', [
            'todayRecord' => $intern->attendanceRecords()->whereDate('attendance_date', $today)->first(),
            'recentRecords' => $intern->attendanceRecords()->latest('attendance_date')->limit(10)->get(),
            'leaveRequests' => $intern->leaveRequests()->latest()->limit(5)->get(),
            'stats' => [
                'hadir' => $intern->attendanceRecords()->whereIn('check_in_status', ['hadir', 'tepat_waktu'])->count(),
                'terlambat' => $intern->attendanceRecords()->where('check_in_status', 'terlambat')->count(),
                'izin' => $intern->leaveRequests()->where('type', 'izin')->where('status', 'approved')->count(),
                'sakit' => $intern->leaveRequests()->where('type', 'sakit')->where('status', 'approved')->count(),
            ],
        ]);
    }

    public function scan(AttendanceSession $session): View|RedirectResponse
    {
        if (! $session->isAvailable()) {
            return redirect()->route('intern.attendance.index')->with('error', 'Sesi QR absensi sudah tidak aktif atau sudah kedaluwarsa.');
        }

        return view('intern.attendance.scan', compact('session'));
    }

    public function store(Request $request, AttendanceSession $session): RedirectResponse
    {
        if (! $session->isAvailable()) {
            return back()->with('error', 'Sesi QR absensi sudah tidak aktif atau sudah kedaluwarsa.');
        }

        $validated = $request->validate([
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $intern = $request->user('intern');
        $record = $intern->attendanceRecords()->firstOrCreate([
            'attendance_date' => $session->attendance_date->toDateString(),
        ]);

        $now = now();
        $distance = $this->attendanceService->distanceMeters(
            isset($validated['latitude']) ? (float) $validated['latitude'] : null,
            isset($validated['longitude']) ? (float) $validated['longitude'] : null,
            $session->latitude !== null ? (float) $session->latitude : null,
            $session->longitude !== null ? (float) $session->longitude : null,
        );
        $locationStatus = $this->attendanceService->locationStatus($session, $distance);
        $timeStatus = $this->attendanceService->timeStatus($session->type, $now);
        $status = in_array($locationStatus, ['valid', 'gps_tidak_dikonfigurasi'], true)
            ? ($session->type === 'datang' ? $timeStatus : 'tepat_waktu')
            : AttendanceRecord::STATUS_PENDING;

        if ($session->type === 'datang') {
            if ($record->check_in_at) {
                return back()->with('error', 'Anda sudah melakukan absen datang hari ini.');
            }

            $record->update([
                'check_in_session_id' => $session->id,
                'check_in_at' => $now,
                'check_in_latitude' => $validated['latitude'] ?? null,
                'check_in_longitude' => $validated['longitude'] ?? null,
                'check_in_distance_meters' => $distance,
                'check_in_status' => $status,
            ]);
        } else {
            if (! $record->check_in_at) {
                return back()->with('error', 'Absen datang harus dilakukan terlebih dahulu.');
            }
            if ($record->check_out_at) {
                return back()->with('error', 'Anda sudah melakukan absen pulang hari ini.');
            }

            $record->update([
                'check_out_session_id' => $session->id,
                'check_out_at' => $now,
                'check_out_latitude' => $validated['latitude'] ?? null,
                'check_out_longitude' => $validated['longitude'] ?? null,
                'check_out_distance_meters' => $distance,
                'check_out_status' => $status,
            ]);
        }

        $message = $status === AttendanceRecord::STATUS_PENDING
            ? 'Absensi tercatat dan menunggu verifikasi admin karena lokasi berada di luar radius.'
            : 'Absensi berhasil dicatat.';

        return redirect()->route('intern.attendance.index')->with('success', $message);
    }

    public function history(Request $request): View
    {
        return view('intern.attendance.history', [
            'records' => $request->user('intern')->attendanceRecords()->latest('attendance_date')->paginate(20),
        ]);
    }
}
