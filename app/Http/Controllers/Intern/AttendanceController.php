<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Services\AttendanceLeaveExceptionService;
use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        private readonly AttendanceService $attendanceService,
        private readonly AttendanceLeaveExceptionService $attendanceLeaveExceptionService,
    ) {}

    public function index(Request $request): View
    {
        $intern = $request->user('intern');
        $today = now()->toDateString();
        $attendanceRecords = $intern->attendanceRecords()->get();

        return view('intern.attendance.index', [
            'todayRecord' => $attendanceRecords->first(fn (AttendanceRecord $record): bool => $record->attendance_date->toDateString() === $today),
            'leaveRequests' => $intern->leaveRequests()->latest()->limit(5)->get(),
            'stats' => [
                'hadir' => $attendanceRecords->whereIn('check_in_status', AttendanceRecord::PRESENT_STATUSES)->count(),
                'alpa' => $intern->alphaDays(),
                'izin' => $intern->approvedLeaveDays('izin'),
                'sakit' => $intern->approvedLeaveDays('sakit'),
                'working_duration' => $this->workingDuration($attendanceRecords->sum(fn (AttendanceRecord $record): int => $record->working_minutes)),
            ],
        ]);
    }

    public function scan(Request $request, AttendanceSession $session): View|RedirectResponse
    {
        if (! $session->isAvailable()) {
            return redirect()->route('intern.attendance.index')->with('error', 'Sesi QR absensi sudah tidak aktif atau sudah kedaluwarsa.');
        }

        $leaveException = $this->attendanceLeaveExceptionService->activeFor($request->user('intern'), now()->toDateString());

        if ($session->type === 'pulang' && ! $leaveException && now()->format('H:i') < config('attendance.check_out_time', '16:00')) {
            return redirect()->route('intern.attendance.index')->with('error', 'Absen pulang baru dapat dilakukan mulai pukul '.config('attendance.check_out_time', '16:00').'.');
        }

        return view('intern.attendance.scan', compact('session', 'leaveException'));
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
        $attendanceDate = now()->toDateString();
        $leaveException = $this->attendanceLeaveExceptionService->activeFor($intern, $attendanceDate);
        if ($session->type === 'pulang' && ! $leaveException && now()->format('H:i') < config('attendance.check_out_time', '16:00')) {
            return back()->with('error', 'Absen pulang baru dapat dilakukan mulai pukul '.config('attendance.check_out_time', '16:00').'.');
        }
        $application = $intern->internshipApplication;
        if ($application && ($attendanceDate < $application->periode_mulai->toDateString() || $attendanceDate > $application->periode_selesai->toDateString())) {
            return back()->with('error', 'Anda hanya dapat melakukan absensi selama periode magang.');
        }

        $approvedLeave = $intern->leaveRequests()
            ->where('status', 'approved')
            ->where('attendance_exception', false)
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

        $record = $intern->attendanceRecords()
            ->whereDate('attendance_date', $attendanceDate)
            ->first();

        if (! $record) {
            $record = $intern->attendanceRecords()->create([
                'attendance_date' => $attendanceDate,
            ]);
        }

        $timeStatus = $this->attendanceService->timeStatus($session->type, $now);
        $status = $leaveException?->status === 'pending'
            ? AttendanceRecord::STATUS_LEAVE_PENDING
            : ($leaveException ? AttendanceRecord::STATUS_ON_TIME : ($session->type === 'datang' ? $timeStatus : AttendanceRecord::STATUS_ON_TIME));

        if ($session->type === 'datang') {
            if ($record->check_in_at) {
                return back()->with('error', 'Anda sudah melakukan absen datang hari ini.');
            }

            $record->update([
                'check_in_session_id' => $session->id,
                'check_in_leave_request_id' => $leaveException?->id,
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
                'check_out_leave_request_id' => $leaveException?->id,
                'check_out_at' => $now,
                'check_out_latitude' => $validated['latitude'] ?? null,
                'check_out_longitude' => $validated['longitude'] ?? null,
                'check_out_distance_meters' => $distance,
                'check_out_status' => $status,
            ]);
        }

        $message = $leaveException?->status === 'pending'
            ? 'Absensi dicatat sementara dan menunggu persetujuan izin admin paling lambat 3×24 jam.'
            : 'Absensi berhasil dicatat.';

        return redirect()->route('intern.attendance.index')->with('success', $message);
    }

    public function history(Request $request): View
    {
        return view('intern.attendance.history', [
            'records' => $request->user('intern')->attendanceRecords()->latest('attendance_date')->paginate(20),
        ]);
    }

    private function workingDuration(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        return $remainingMinutes > 0 ? $hours.' jam '.$remainingMinutes.' menit' : $hours.' jam';
    }
}
