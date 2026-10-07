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
            'leaveRequests' => $intern->leaveRequests()->latest()->limit(5)->get(),
            'stats' => [
                'hadir' => $intern->attendanceRecords()->whereIn('check_in_status', ['hadir', 'tepat_waktu', 'terlambat'])->count(),
                'alpa' => $intern->alphaDays(),
                'izin' => $intern->approvedLeaveDays('izin'),
                'sakit' => $intern->approvedLeaveDays('sakit'),
            ],
        ]);
    }

    public function scan(AttendanceSession $session): View|RedirectResponse
    {
        if (! $session->isAvailable()) {
            return redirect()->route('intern.attendance.index')->with('error', 'Sesi QR absensi sudah tidak aktif atau sudah kedaluwarsa.');
        }

        if ($session->type === 'pulang' && now()->format('H:i') < config('attendance.check_out_time', '16:00')) {
            return redirect()->route('intern.attendance.index')->with('error', 'Absen pulang baru dapat dilakukan mulai pukul '.config('attendance.check_out_time', '16:00').'.');
        }

        return view('intern.attendance.scan', compact('session'));
    }

    public function store(Request $request, AttendanceSession $session): RedirectResponse
    {
        if (! $session->isAvailable()) {
            return back()->with('error', 'Sesi QR absensi sudah tidak aktif atau sudah kedaluwarsa.');
        }

        if ($session->type === 'pulang' && now()->format('H:i') < config('attendance.check_out_time', '16:00')) {
            return back()->with('error', 'Absen pulang baru dapat dilakukan mulai pukul '.config('attendance.check_out_time', '16:00').'.');
        }

        $validated = $request->validate([
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $intern = $request->user('intern');
        $attendanceDate = now()->toDateString();
        $application = $intern->internshipApplication;
        if ($application && ($attendanceDate < $application->periode_mulai->toDateString() || $attendanceDate > $application->periode_selesai->toDateString())) {
            return back()->with('error', 'Anda hanya dapat melakukan absensi selama periode magang.');
        }

        $approvedLeave = $intern->leaveRequests()
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $attendanceDate)
            ->whereDate('end_date', '>=', $attendanceDate)
            ->first();

        if ($approvedLeave) {
            return back()->with('error', 'Anda tidak perlu absen pada tanggal yang sudah disetujui sebagai '.($approvedLeave->type === 'sakit' ? 'sakit' : 'izin').'.');
        }

        $now = now();
        $distance = $this->attendanceService->distanceMeters(
            isset($validated['latitude']) ? (float) $validated['latitude'] : null,
            isset($validated['longitude']) ? (float) $validated['longitude'] : null,
            $session->latitude !== null ? (float) $session->latitude : null,
            $session->longitude !== null ? (float) $session->longitude : null,
        );
        $locationStatus = $this->attendanceService->locationStatus($session, $distance);
        if ($locationStatus === 'di_luar_radius') {
            return back()->with('error', 'Absensi ditolak. Jarak Anda '.number_format($distance).' meter dari lokasi, sedangkan radius maksimal adalah '.$session->radius_meters.' meter.');
        }

        if ($locationStatus === 'menunggu_verifikasi') {
            return back()->with('error', 'Absensi ditolak karena lokasi perangkat tidak dapat diverifikasi. Aktifkan GPS dan coba lagi.');
        }

        $record = $intern->attendanceRecords()->firstOrCreate([
            'attendance_date' => $attendanceDate,
        ]);

        $timeStatus = $this->attendanceService->timeStatus($session->type, $now);
        $status = $session->type === 'datang' ? $timeStatus : 'tepat_waktu';

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

        return redirect()->route('intern.attendance.index')->with('success', 'Absensi berhasil dicatat.');
    }

    public function history(Request $request): View
    {
        return view('intern.attendance.history', [
            'records' => $request->user('intern')->attendanceRecords()->latest('attendance_date')->paginate(20),
        ]);
    }
}
