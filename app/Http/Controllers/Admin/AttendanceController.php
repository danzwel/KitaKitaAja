<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\LeaveRequest;
use App\Models\Intern;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Dompdf\Dompdf;
use Dompdf\Options;

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
            $leaveQuery->where('intern_id', $selectedIntern->id)
                ->whereDate('start_date', '<=', $periodEnd)
                ->whereDate('end_date', '>=', $periodStart);
        }
        $leaveRequests = $leaveQuery->latest()->get();

        $summaryBase = (clone $recordQuery);

        return view('admin.attendance.index', [
            'date' => $date,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'interns' => $interns,
            'selectedIntern' => $selectedIntern,
            'sessions' => AttendanceSession::where(function ($query) use ($date): void {
                $query->where('is_recurring', true)->orWhereDate('attendance_date', $date);
            })->latest()->get(),
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
        $todayRecordsQuery = AttendanceRecord::with('intern')->whereDate('attendance_date', now()->toDateString());

        $summaryRows = $interns->map(function (Intern $intern): array {
            $application = $intern->internshipApplication;
            $periodStart = $application?->periode_mulai?->copy()->startOfDay() ?? now()->startOfDay();
            $accountStart = $intern->created_at?->copy()->startOfDay() ?? $periodStart;
            $start = $periodStart->max($accountStart);
            $end = $application?->periode_selesai?->copy()->endOfDay() ?? now()->endOfDay();

            $countEnd = $end->copy()->min(now()->endOfDay());
            $records = $start->gt($countEnd)
                ? collect()
                : AttendanceRecord::where('intern_id', $intern->id)->whereBetween('attendance_date', [$start->toDateString(), $countEnd->toDateString()])->get();
            $attendanceSessionDates = collect();
            // Hari berjalan belum dianggap alpa; alpa baru diputuskan mulai hari berikutnya.
            $alphaEnd = $countEnd->copy()->subDay()->endOfDay();
            if ($start->lte($alphaEnd)) {
                $sessionQuery = AttendanceSession::where('type', 'datang')->where(function ($query) use ($start, $countEnd): void {
                    $query->where('is_recurring', true)
                        ->orWhereBetween('attendance_date', [$start->toDateString(), $countEnd->toDateString()]);
                });

                $sessionQuery->get()->each(function (AttendanceSession $session) use (&$attendanceSessionDates, $start, $alphaEnd): void {
                    if ($session->is_recurring) {
                        $sessionStart = $session->created_at?->copy()->startOfDay() ?? $start->copy()->startOfDay();
                        $cursor = $start->copy()->max($sessionStart)->startOfDay();
                        while ($cursor->lte($alphaEnd)) {
                            $attendanceSessionDates->push($cursor->toDateString());
                            $cursor->addDay();
                        }
                    } elseif ($session->attendance_date && $session->attendance_date->lte($alphaEnd)) {
                        $attendanceSessionDates->push($session->attendance_date->toDateString());
                    }
                });

                $attendanceSessionDates = $attendanceSessionDates->unique()->values();
            }
            $presentDates = $records->filter(fn (AttendanceRecord $record) => in_array($record->check_in_status, ['hadir', 'tepat_waktu', 'terlambat'], true))->map(fn (AttendanceRecord $record) => $record->attendance_date->toDateString())->unique();
            $pending = $records->where('check_in_status', 'menunggu_verifikasi')->count();
            $leaveDays = ['izin' => [], 'sakit' => []];

            $holidays = collect(config('attendance.holidays', []))->filter()->map(fn (string $date): string => Carbon::parse($date)->toDateString())->all();

            // Hanya hitung izin/sakit yang tanggalnya sudah berjalan agar rekap
            // mengikuti kondisi real-time sampai hari ini.
            if ($start->lte($countEnd)) LeaveRequest::where('intern_id', $intern->id)->where('status', 'approved')->whereDate('start_date', '<=', $countEnd->toDateString())->whereDate('end_date', '>=', $start->toDateString())->get()->each(function (LeaveRequest $leave) use (&$leaveDays, $start, $countEnd, $holidays): void {
                $cursor = $leave->start_date->copy()->max($start->copy()->startOfDay());
                $last = $leave->end_date->copy()->min($countEnd->copy()->startOfDay());
                while ($cursor->lte($last)) {
                    if (! $cursor->isWeekend() && ! in_array($cursor->toDateString(), $holidays, true)) {
                        $leaveDays[$leave->type === 'sakit' ? 'sakit' : 'izin'][$cursor->toDateString()] = true;
                    }
                    $cursor->addDay();
                }
            });

            $workingDays = 0;
            $cursor = $start->copy()->startOfDay();
            while ($cursor->lte($countEnd)) {
                if (! $cursor->isWeekend() && ! in_array($cursor->toDateString(), $holidays, true)) $workingDays++;
                $cursor->addDay();
            }

            $present = $presentDates->count();
            $izin = count($leaveDays['izin']);
            $sakit = count($leaveDays['sakit']);
            $scheduledWorkingDays = $attendanceSessionDates->filter(function (string $date) use ($holidays): bool {
                $day = Carbon::parse($date);

                return ! $day->isWeekend() && ! in_array($date, $holidays, true);
            })->count();

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
                'alpha' => max(0, $scheduledWorkingDays - $present - $izin - $sakit),
            ];
        })->values();

        return view('admin.attendance.recap', [
            'interns' => $interns,
            'summaryRows' => $summaryRows,
            'todayRecords' => $todayRecordsQuery->latest('check_in_at')->get(),
            'leaveRequests' => LeaveRequest::with('intern')->where('status', 'pending')->latest()->get(),
        ]);
    }

    public function internRecap(Intern $intern): View
    {
        $application = $intern->internshipApplication;
        $periodStart = $application?->periode_mulai?->copy()->startOfDay() ?? now()->startOfDay();
        $accountStart = $intern->created_at?->copy()->startOfDay() ?? $periodStart;
        $start = $periodStart->max($accountStart);
        $end = $application?->periode_selesai?->copy()->endOfDay() ?? now()->endOfDay();
        $countEnd = $end->copy()->min(now()->endOfDay());

        $records = $start->lte($countEnd)
            ? $intern->attendanceRecords()->whereBetween('attendance_date', [$start->toDateString(), $countEnd->toDateString()])->latest('attendance_date')->get()
            : collect();
        $recordsByDate = $records->keyBy(fn (AttendanceRecord $record): string => $record->attendance_date->toDateString());
        $holidays = collect(config('attendance.holidays', []))->filter()->map(fn (string $date): string => Carbon::parse($date)->toDateString())->all();
        $scheduledDates = [];
        $alphaEnd = $countEnd->copy()->subDay()->endOfDay();
        if ($start->lte($alphaEnd)) {
            AttendanceSession::where('type', 'datang')->where(function ($query) use ($start, $countEnd): void {
                $query->where('is_recurring', true)->orWhereBetween('attendance_date', [$start->toDateString(), $countEnd->toDateString()]);
            })->get()->each(function (AttendanceSession $session) use (&$scheduledDates, $start, $alphaEnd, $holidays): void {
                if ($session->is_recurring) {
                    $cursor = $start->copy()->max($session->created_at?->copy()->startOfDay() ?? $start->copy())->startOfDay();
                    while ($cursor->lte($alphaEnd)) {
                        if ($cursor->isWeekday() && ! in_array($cursor->toDateString(), $holidays, true)) $scheduledDates[$cursor->toDateString()] = true;
                        $cursor->addDay();
                    }
                } elseif ($session->attendance_date && $session->attendance_date->lte($alphaEnd) && $session->attendance_date->isWeekday() && ! in_array($session->attendance_date->toDateString(), $holidays, true)) {
                    $scheduledDates[$session->attendance_date->toDateString()] = true;
                }
            });
        }
        $approvedLeaves = $intern->leaveRequests()->where('status', 'approved')->whereDate('start_date', '<=', $countEnd->toDateString())->whereDate('end_date', '>=', $start->toDateString())->get();
        $attendanceHistory = collect();
        if ($start->lte($countEnd)) {
            $cursor = $start->copy()->startOfDay();
            while ($cursor->lte($countEnd)) {
                if ($cursor->isWeekday() && ! in_array($cursor->toDateString(), $holidays, true)) {
                    $date = $cursor->toDateString();
                    $record = $recordsByDate->get($date);
                    $leave = $approvedLeaves->first(fn (LeaveRequest $request): bool => $request->start_date->lte($cursor) && $request->end_date->gte($cursor));
                    $status = $record?->check_in_status ? ucwords(str_replace('_', ' ', $record->check_in_status)) : ($leave ? ucfirst($leave->type) : (isset($scheduledDates[$date]) && $cursor->lt(now()->startOfDay()) ? 'Alpa' : 'Belum ada absensi'));
                    $attendanceHistory->push(['date' => $cursor->copy(), 'record' => $record, 'status' => $status]);
                }
                $cursor->addDay();
            }
            $attendanceHistory = $attendanceHistory->sortByDesc(fn (array $item): string => $item['date']->toDateString())->values();
        }

        return view('admin.attendance.intern', [
            'intern' => $intern,
            'application' => $application,
            'start' => $start,
            'end' => $end,
            'records' => $records,
            'attendanceHistory' => $attendanceHistory,
            'leaveRequests' => $intern->leaveRequests()->latest()->get(),
        ]);
    }

    public function recapPdf(Request $request)
    {
        $data = $this->recap($request)->getData();

        return $this->downloadPdf(
            view('admin.attendance.pdf.recap', $data)->render(),
            'rekap-absensi-semua-mahasiswa.pdf'
        );
    }

    public function internRecapPdf(Intern $intern)
    {
        $data = $this->internRecap($intern)->getData();

        return $this->downloadPdf(
            view('admin.attendance.pdf.intern', $data)->render(),
            'rekap-absensi-'.$intern->username.'.pdf'
        );
    }

    private function downloadPdf(string $html, string $filename)
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $pdf = new Dompdf($options);
        $pdf->loadHtml($html, 'UTF-8');
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function storeSession(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:datang,pulang'],
            'attendance_date' => ['nullable', 'date', 'required_unless:is_recurring,1'],
            'is_recurring' => ['nullable', 'boolean'],
            'expires_at' => ['nullable', 'date'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'radius_meters' => ['required', 'integer', 'min:10', 'max:5000'],
        ]);

        $isRecurring = (bool) ($validated['is_recurring'] ?? false);
        AttendanceSession::where('type', $validated['type'])
            ->where(function ($query) use ($isRecurring, $validated): void {
                $query->where('is_recurring', $isRecurring)
                    ->when(! $isRecurring, fn ($query) => $query->whereDate('attendance_date', $validated['attendance_date']));
            })
            ->update(['is_active' => false]);

        AttendanceSession::create([
            ...$validated,
            'attendance_date' => $isRecurring ? null : $validated['attendance_date'],
            'is_recurring' => $isRecurring,
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
