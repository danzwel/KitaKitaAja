<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\LeaveRequest;
use App\Models\Intern;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $interns = Intern::with('internshipApplication')->where('status', Intern::STATUS_AKTIF)->orderBy('name')->get();
        $selectedIntern = $request->filled('intern_id') ? $interns->firstWhere('id', (int) $request->input('intern_id')) : null;
        $application = $selectedIntern?->internshipApplication;
        $defaultStart = $application?->periode_mulai?->toDateString() ?? now()->toDateString();
        $defaultEnd = $application?->periode_selesai?->toDateString() ?? now()->toDateString();
        $periodStart = $request->input('start_date', $defaultStart);
        $periodEnd = $request->input('end_date', $defaultEnd);
        $date = $request->date('date')?->toDateString() ?? now()->toDateString();

        $recordQuery = AttendanceRecord::with('intern');
        if ($selectedIntern) {
            $recordQuery->where('intern_id', $selectedIntern->id)->whereBetween('attendance_date', [$periodStart, $periodEnd]);
        } else {
            $recordQuery->whereDate('attendance_date', $date);
        }
        $records = $recordQuery->latest('attendance_date')->latest('check_in_at')->get();

        $leaveQuery = LeaveRequest::with('intern')->where('status', 'pending');
        if ($selectedIntern) {
            $leaveQuery->where('intern_id', $selectedIntern->id)->where(function ($query) use ($periodStart, $periodEnd): void {
                $query->whereBetween('start_date', [$periodStart, $periodEnd])->orWhereBetween('end_date', [$periodStart, $periodEnd]);
            });
        }
        $leaveRequests = $leaveQuery->latest()->get();

        $summaryBase = (clone $recordQuery);

        return view('admin.attendance.index', [
            'date' => $date,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'interns' => $interns,
            'selectedIntern' => $selectedIntern,
            'sessions' => AttendanceSession::whereDate('attendance_date', $date)->latest()->get(),
            'records' => $records,
            'leaveRequests' => $leaveRequests,
            'summary' => [
                'total' => $summaryBase->count(),
                'hadir' => (clone $summaryBase)->whereIn('check_in_status', ['hadir', 'tepat_waktu'])->count(),
                'pending' => (clone $summaryBase)->where('check_in_status', 'menunggu_verifikasi')->count(),
                'leave_pending' => $leaveRequests->count(),
            ],
        ]);
    }

    public function recap(Request $request): View
    {
        $interns = Intern::with('internshipApplication')->where('status', Intern::STATUS_AKTIF)->orderBy('name')->get();
        $selectedIntern = $request->filled('intern_id') ? $interns->firstWhere('id', (int) $request->input('intern_id')) : null;
        $application = $selectedIntern?->internshipApplication;
        $periodStart = $request->input('start_date', $application?->periode_mulai?->toDateString() ?? now()->toDateString());
        $periodEnd = $request->input('end_date', $application?->periode_selesai?->toDateString() ?? now()->toDateString());

        $todayRecordsQuery = AttendanceRecord::with('intern')->whereDate('attendance_date', now()->toDateString());
        if ($selectedIntern) {
            $todayRecordsQuery->where('intern_id', $selectedIntern->id);
        }

        $summaryRows = $interns->when($selectedIntern, fn ($collection) => $collection->filter(fn (Intern $intern) => $intern->id === $selectedIntern->id))->map(function (Intern $intern) use ($selectedIntern, $periodStart, $periodEnd): array {
            $application = $intern->internshipApplication;
            $start = $selectedIntern
                ? \Carbon\Carbon::parse($periodStart)->startOfDay()
                : ($application?->periode_mulai?->copy()->startOfDay() ?? now()->startOfDay());
            $end = $selectedIntern
                ? \Carbon\Carbon::parse($periodEnd)->endOfDay()
                : ($application?->periode_selesai?->copy()->endOfDay() ?? now()->endOfDay());

            $countEnd = $end->copy()->min(now()->endOfDay());
            $records = $start->gt($countEnd)
                ? collect()
                : AttendanceRecord::where('intern_id', $intern->id)->whereBetween('attendance_date', [$start->toDateString(), $countEnd->toDateString()])->get();
            $presentDates = $records->filter(fn (AttendanceRecord $record) => in_array($record->check_in_status, ['hadir', 'tepat_waktu', 'terlambat'], true))->map(fn (AttendanceRecord $record) => $record->attendance_date->toDateString())->unique();
            $pending = $records->where('check_in_status', 'menunggu_verifikasi')->count();
            $leaveDays = ['izin' => [], 'sakit' => []];

            if ($start->lte($countEnd)) LeaveRequest::where('intern_id', $intern->id)->where('status', 'approved')->where(function ($query) use ($start, $countEnd): void {
                $query->whereBetween('start_date', [$start->toDateString(), $countEnd->toDateString()])->orWhereBetween('end_date', [$start->toDateString(), $countEnd->toDateString()]);
            })->get()->each(function (LeaveRequest $leave) use (&$leaveDays, $start, $countEnd): void {
                $cursor = $leave->start_date->copy()->max($start->copy()->startOfDay());
                $last = $leave->end_date->copy()->min($countEnd->copy()->startOfDay());
                while ($cursor->lte($last)) {
                    if (! $cursor->isWeekend()) {
                        $leaveDays[$leave->type === 'sakit' ? 'sakit' : 'izin'][$cursor->toDateString()] = true;
                    }
                    $cursor->addDay();
                }
            });

            $workingDays = 0;
            $cursor = $start->copy()->startOfDay();
            while ($cursor->lte($countEnd)) {
                if (! $cursor->isWeekend()) $workingDays++;
                $cursor->addDay();
            }

            $present = $presentDates->count();
            $izin = count($leaveDays['izin']);
            $sakit = count($leaveDays['sakit']);

            return [
                'intern' => $intern,
                'period_start' => $start->toDateString(),
                'period_end' => $end->toDateString(),
                'working_days' => $workingDays,
                'present' => $present,
                'late' => $records->where('check_in_status', 'terlambat')->count(),
                'izin' => $izin,
                'sakit' => $sakit,
                'pending' => $pending,
                'alpha' => max(0, $workingDays - $present - $izin - $sakit),
            ];
        })->values();

        return view('admin.attendance.recap', [
            'interns' => $interns,
            'selectedIntern' => $selectedIntern,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'summaryRows' => $summaryRows,
            'todayRecords' => $todayRecordsQuery->latest('check_in_at')->get(),
            'summary' => [
                'total' => $summaryRows->sum('working_days'),
                'hadir' => $summaryRows->sum('present'),
                'pending' => $summaryRows->sum('pending'),
                'leave_pending' => $summaryRows->sum('izin') + $summaryRows->sum('sakit'),
            ],
        ]);
    }

    public function storeSession(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:datang,pulang'],
            'attendance_date' => ['required', 'date'],
            'expires_at' => ['nullable', 'date'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'radius_meters' => ['required', 'integer', 'min:10', 'max:5000'],
        ]);

        AttendanceSession::where('type', $validated['type'])
            ->whereDate('attendance_date', $validated['attendance_date'])
            ->update(['is_active' => false]);

        AttendanceSession::create([
            ...$validated,
            'created_by' => $request->user('admin')->id,
            'token' => Str::random(48),
            'is_active' => true,
        ]);

        return back()->with('success', 'Sesi QR absensi berhasil dibuat.');
    }

    public function closeSession(AttendanceSession $session): RedirectResponse
    {
        $session->update(['is_active' => false]);

        return back()->with('success', 'Sesi QR absensi ditutup.');
    }

    public function reviewAttendance(Request $request, AttendanceRecord $record): RedirectResponse
    {
        $validated = $request->validate([
            'decision' => ['required', 'in:approve,reject'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $record->update([
            'check_in_status' => $validated['decision'] === 'approve' ? AttendanceRecord::STATUS_PRESENT : AttendanceRecord::STATUS_REJECTED,
            'admin_note' => $validated['admin_note'] ?? null,
        ]);

        return back()->with('success', 'Status absensi berhasil diperbarui.');
    }

    public function reviewLeave(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $validated = $request->validate([
            'decision' => ['required', 'in:approved,rejected'],
            'review_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $leaveRequest->update([
            'status' => $validated['decision'],
            'reviewed_by' => $request->user('admin')->id,
            'reviewed_at' => now(),
            'review_note' => $validated['review_note'] ?? null,
        ]);

        return back()->with('success', 'Pengajuan ketidakhadiran berhasil diproses.');
    }
}
