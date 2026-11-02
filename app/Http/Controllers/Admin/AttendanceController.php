<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\LeaveRequest;
use App\Models\Intern;
use App\Services\AttendanceLeaveExceptionService;
use App\Services\AttendanceExcelExportService;
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
        app(AttendanceLeaveExceptionService::class)->expireOverdue();
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
            $recordQuery->where('intern_id', $selectedIntern->id)
                ->whereDate('attendance_date', '>=', $periodStart)
                ->whereDate('attendance_date', '<=', $periodEnd);
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
                'pending' => (clone $summaryBase)->whereIn('check_in_status', ['menunggu_verifikasi', AttendanceRecord::STATUS_LEAVE_PENDING])->count(),
                'leave_pending' => $leaveRequests->count(),
            ],
        ]);
    }

    public function recap(Request $request): View
    {
        app(AttendanceLeaveExceptionService::class)->expireOverdue();
        $request->validate([
            'date' => ['nullable', 'date'],
            'year' => ['nullable', 'integer', 'between:2000,2100'],
        ]);
        $attendanceDate = Carbon::parse($request->input('date', now()->toDateString()))->startOfDay();
        $currentYear = (int) now()->year;
        $selectedYear = (int) $request->integer('year', $currentYear);
        $years = Intern::with('internshipApplication:id,periode_mulai')
            ->get()
            ->map(fn (Intern $intern): ?int => $intern->internshipApplication?->periode_mulai?->year ?? $intern->created_at?->year)
            ->filter()
            ->prepend($currentYear)
            ->unique()
            ->sortDesc()
            ->values();
        // Rekap adalah arsip kehadiran, sehingga peserta yang sudah selesai
        // tetap harus tersedia untuk ditinjau dan diekspor. Peserta aktif
        // ditampilkan lebih dahulu agar kebutuhan operasional harian tetap jelas.
        $interns = Intern::with('internshipApplication')
            ->where(function ($query) use ($selectedYear): void {
                $query->whereHas('internshipApplication', fn ($applicationQuery) => $applicationQuery->whereYear('periode_mulai', $selectedYear))
                    // Data peserta lama yang belum memiliki pengajuan versi baru
                    // dikelompokkan berdasarkan tahun pembuatan akunnya.
                    ->orWhere(function ($legacyQuery) use ($selectedYear): void {
                        $legacyQuery->whereDoesntHave('internshipApplication')
                            ->whereYear('created_at', $selectedYear);
                    });
            })
            ->orderByRaw('CASE WHEN status = ? THEN 0 ELSE 1 END', [Intern::STATUS_AKTIF])
            ->orderBy('name')
            ->get();
        $todayRecordsQuery = AttendanceRecord::with('intern')->whereDate('attendance_date', $attendanceDate->toDateString());

        $summaryRows = $interns->map(function (Intern $intern): array {
            $application = $intern->internshipApplication;
            $periodStart = $application?->periode_mulai?->copy()->startOfDay() ?? now()->startOfDay();
            $accountStart = $intern->created_at?->copy()->startOfDay() ?? $periodStart;
            $start = $periodStart->max($accountStart);
            $end = $application?->periode_selesai?->copy()->endOfDay() ?? now()->endOfDay();

            $countEnd = $end->copy()->min(now()->endOfDay());
            $records = $start->gt($countEnd)
                ? collect()
                : AttendanceRecord::where('intern_id', $intern->id)
                    ->whereDate('attendance_date', '>=', $start->toDateString())
                    ->whereDate('attendance_date', '<=', $countEnd->toDateString())
                    ->get();
            $attendanceSessionDates = collect();
            // Hari yang sedang berjalan belum dianggap alpa. Namun, apabila
            // periode magang sudah berlalu, hari terakhir periode tetap harus
            // mendapat status agar jumlah rekap dapat dipertanggungjawabkan.
            $alphaEnd = $countEnd->isToday()
                ? $countEnd->copy()->subDay()->endOfDay()
                : $countEnd->copy()->endOfDay();
            if ($start->lte($alphaEnd)) {
                $sessionQuery = AttendanceSession::where('type', 'datang')->where(function ($query) use ($start, $countEnd): void {
                    $query->where('is_recurring', true)
                        ->orWhere(function ($dateQuery) use ($start, $countEnd): void {
                            $dateQuery->whereDate('attendance_date', '>=', $start->toDateString())
                                ->whereDate('attendance_date', '<=', $countEnd->toDateString());
                        });
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
            $pending = $records->whereIn('check_in_status', ['menunggu_verifikasi', AttendanceRecord::STATUS_LEAVE_PENDING])->count();
            $leaveDays = ['izin' => [], 'sakit' => []];

            $holidays = collect(config('attendance.holidays', []))->filter()->map(fn (string $date): string => Carbon::parse($date)->toDateString())->all();

            // Hanya hitung izin/sakit yang tanggalnya sudah berjalan agar rekap
            // mengikuti kondisi real-time sampai hari ini.
            if ($start->lte($countEnd)) LeaveRequest::where('intern_id', $intern->id)->where('status', 'approved')->where('attendance_exception', false)->whereDate('start_date', '<=', $countEnd->toDateString())->whereDate('end_date', '>=', $start->toDateString())->get()->each(function (LeaveRequest $leave) use (&$leaveDays, $start, $countEnd, $holidays): void {
                $cursor = $leave->start_date->copy()->max($start->copy()->startOfDay());
                $last = $leave->end_date->copy()->min($countEnd->copy()->startOfDay());
                while ($cursor->lte($last)) {
                    if (! $cursor->isWeekend() && ! in_array($cursor->toDateString(), $holidays, true)) {
                        $leaveDays[$leave->type === 'sakit' ? 'sakit' : 'izin'][$cursor->toDateString()] = true;
                    }
                    $cursor->addDay();
                }
            });

            $present = $presentDates->count();
            $izin = count($leaveDays['izin']);
            $sakit = count($leaveDays['sakit']);
            $pendingDates = $records->whereIn('check_in_status', ['menunggu_verifikasi', AttendanceRecord::STATUS_LEAVE_PENDING])
                ->map(fn (AttendanceRecord $record): string => $record->attendance_date->toDateString())
                ->unique()
                ->values();
            $scheduledWorkingDates = $attendanceSessionDates->filter(function (string $date) use ($holidays): bool {
                $day = Carbon::parse($date);

                return ! $day->isWeekend() && ! in_array($date, $holidays, true);
            })->unique()->values();
            $resolvedDates = $presentDates
                ->merge($pendingDates)
                ->merge(array_keys($leaveDays['izin']))
                ->merge(array_keys($leaveDays['sakit']))
                ->unique()
                ->flip();
            $alpha = $scheduledWorkingDates
                ->reject(fn (string $date): bool => $resolvedDates->has($date))
                ->count();
            // Hari tercatat adalah hari kerja yang telah memiliki sesi QR atau
            // status kehadiran. Dengan dasar yang sama, jumlah kategori rekap
            // tidak lagi meninggalkan hari tanpa status.
            $workingDays = $scheduledWorkingDates
                ->merge($resolvedDates->keys())
                ->unique()
                ->count();
            $workingMinutes = $records->sum(fn (AttendanceRecord $record): int => $record->working_minutes);

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
                'alpha' => $alpha,
                'working_minutes' => $workingMinutes,
                'working_duration' => $this->formatWorkingMinutes($workingMinutes),
            ];
        })->values();

        return view('admin.attendance.recap', [
            'interns' => $interns,
            'summaryRows' => $summaryRows,
            'years' => $years,
            'selectedYear' => $selectedYear,
            'attendanceDate' => $attendanceDate,
            'todayRecords' => $todayRecordsQuery->latest('check_in_at')->get(),
            'leaveRequests' => LeaveRequest::with('intern')->where('status', 'pending')->latest()->get(),
        ]);
    }

    public function internRecap(Intern $intern): View
    {
        app(AttendanceLeaveExceptionService::class)->expireOverdue();
        $application = $intern->internshipApplication;
        $periodStart = $application?->periode_mulai?->copy()->startOfDay() ?? now()->startOfDay();
        $accountStart = $intern->created_at?->copy()->startOfDay() ?? $periodStart;
        $start = $periodStart->max($accountStart);
        $end = $application?->periode_selesai?->copy()->endOfDay() ?? now()->endOfDay();
        $countEnd = $end->copy()->min(now()->endOfDay());

        $records = $start->lte($countEnd)
            ? $intern->attendanceRecords()
                ->whereDate('attendance_date', '>=', $start->toDateString())
                ->whereDate('attendance_date', '<=', $countEnd->toDateString())
                ->latest('attendance_date')
                ->get()
            : collect();
        $workingMinutes = $records->sum(fn (AttendanceRecord $record): int => $record->working_minutes);
        $recordsByDate = $records->keyBy(fn (AttendanceRecord $record): string => $record->attendance_date->toDateString());
        $holidays = collect(config('attendance.holidays', []))->filter()->map(fn (string $date): string => Carbon::parse($date)->toDateString())->all();
        $scheduledDates = [];
        $alphaEnd = $countEnd->isToday()
            ? $countEnd->copy()->subDay()->endOfDay()
            : $countEnd->copy()->endOfDay();
        if ($start->lte($alphaEnd)) {
            AttendanceSession::where('type', 'datang')->where(function ($query) use ($start, $countEnd): void {
                $query->where('is_recurring', true)
                    ->orWhere(function ($dateQuery) use ($start, $countEnd): void {
                        $dateQuery->whereDate('attendance_date', '>=', $start->toDateString())
                            ->whereDate('attendance_date', '<=', $countEnd->toDateString());
                    });
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
        $approvedLeaves = $intern->leaveRequests()->where('status', 'approved')->where('attendance_exception', false)->whereDate('start_date', '<=', $countEnd->toDateString())->whereDate('end_date', '>=', $start->toDateString())->get();
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
            'workingMinutes' => $workingMinutes,
            'workingDuration' => $this->formatWorkingMinutes($workingMinutes),
        ]);
    }

    public function recapPdf(Request $request)
    {
        $data = $this->recap($request)->getData();

        return $this->downloadPdf(
            view('admin.attendance.pdf.recap', $data)->render(),
            'rekap-absensi-semua-peserta.pdf'
        );
    }

    public function recapExcel(Request $request, AttendanceExcelExportService $excel)
    {
        $data = $this->recap($request)->getData();

        return $excel->summary($data['summaryRows']);
    }

    public function dailyAttendancePdf(Request $request)
    {
        $data = $this->recap($request)->getData();
        $date = $data['attendanceDate'];

        return $this->downloadPdf(
            view('admin.attendance.pdf.daily', $data)->render(),
            'absensi-harian-'.$date->format('Y-m-d').'.pdf'
        );
    }

    public function dailyAttendanceExcel(Request $request, AttendanceExcelExportService $excel)
    {
        $data = $this->recap($request)->getData();

        return $excel->daily($data['todayRecords'], $data['attendanceDate']);
    }

    public function internRecapPdf(Intern $intern)
    {
        $data = $this->internRecap($intern)->getData();

        return $this->downloadPdf(
            view('admin.attendance.pdf.intern', $data)->render(),
            'rekap-absensi-'.$intern->username.'.pdf'
        );
    }

    public function internRecapExcel(Intern $intern, AttendanceExcelExportService $excel)
    {
        $data = $this->internRecap($intern)->getData();

        return $excel->intern(
            $intern,
            $data['attendanceHistory'],
            $data['leaveRequests'],
            $data['start'],
            $data['end'],
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

    public function confirmAttendanceManually(Request $request, Intern $intern): RedirectResponse
    {
        $validated = $request->validate([
            'attendance_date' => ['required', 'date', 'before_or_equal:'.now()->toDateString()],
            'confirmation_type' => ['required', 'in:missed_check_in,correct_late_check_in,missed_check_out,missed_check_in_and_out'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $date = Carbon::parse($validated['attendance_date'])->startOfDay();
        $application = $intern->internshipApplication;
        if ($application && ($date->lt($application->periode_mulai->copy()->startOfDay()) || $date->gt($application->periode_selesai->copy()->endOfDay()))) {
            return back()->with('error', 'Tanggal konfirmasi harus berada dalam periode magang peserta.');
        }

        $record = $intern->attendanceRecords()->whereDate('attendance_date', $date->toDateString())->first();
        $adminId = $request->user('admin')->id;
        $type = $validated['confirmation_type'];

        if (in_array($type, ['missed_check_in', 'missed_check_in_and_out'], true)) {
            if ($record?->check_in_at) {
                return back()->with('error', $type === 'missed_check_in_and_out'
                    ? 'Konfirmasi lupa scan datang dan pulang hanya dapat digunakan jika belum ada scan datang.'
                    : 'Absen datang sudah tercatat. Gunakan opsi koreksi jika scan datang tercatat terlambat.');
            }

            $record ??= $intern->attendanceRecords()->create(['attendance_date' => $date->toDateString()]);
            $record->update([
                'check_in_session_id' => null,
                'check_in_leave_request_id' => null,
                'check_in_at' => Carbon::parse($date->toDateString().' '.config('attendance.check_in_time', '07:30')),
                'check_in_latitude' => null,
                'check_in_longitude' => null,
                'check_in_distance_meters' => null,
                'check_in_status' => AttendanceRecord::STATUS_ON_TIME,
                'check_in_confirmed_by' => $adminId,
                'check_in_confirmed_at' => now(),
            ]);
        }

        if ($type === 'correct_late_check_in') {
            if (! $record?->check_in_at) {
                return back()->with('error', 'Belum ada scan datang untuk dikoreksi. Gunakan opsi lupa scan datang.');
            }
            if ($record->check_in_status !== AttendanceRecord::STATUS_LATE) {
                return back()->with('error', 'Koreksi ini hanya digunakan untuk scan datang yang tercatat terlambat.');
            }

            $record->update([
                'check_in_session_id' => null,
                'check_in_leave_request_id' => null,
                'check_in_at' => Carbon::parse($date->toDateString().' '.config('attendance.check_in_time', '07:30')),
                'check_in_latitude' => null,
                'check_in_longitude' => null,
                'check_in_distance_meters' => null,
                'check_in_status' => AttendanceRecord::STATUS_ON_TIME,
                'check_in_confirmed_by' => $adminId,
                'check_in_confirmed_at' => now(),
            ]);
        }

        if (in_array($type, ['missed_check_out', 'missed_check_in_and_out'], true)) {
            if (! $record?->check_in_at) {
                return back()->with('error', 'Konfirmasi pulang hanya dapat dilakukan setelah kehadiran datang tercatat.');
            }
            if ($record->check_out_at) {
                return back()->with('error', 'Absen pulang sudah tercatat pada hari tersebut.');
            }

            $record->update([
                'check_out_session_id' => null,
                'check_out_leave_request_id' => null,
                'check_out_at' => Carbon::parse($date->toDateString().' '.config('attendance.check_out_time', '16:00')),
                'check_out_latitude' => null,
                'check_out_longitude' => null,
                'check_out_distance_meters' => null,
                'check_out_status' => AttendanceRecord::STATUS_ON_TIME,
                'check_out_confirmed_by' => $adminId,
                'check_out_confirmed_at' => now(),
            ]);
        }

        $labels = [
            'missed_check_in' => 'lupa scan datang',
            'correct_late_check_in' => 'koreksi scan datang terlambat',
            'missed_check_out' => 'lupa scan pulang',
            'missed_check_in_and_out' => 'lupa scan datang dan pulang',
        ];
        $note = 'Konfirmasi manual admin: '.$labels[$type].' pada '.now()->format('d M Y H:i').' WIB.';
        if (filled($validated['admin_note'] ?? null)) {
            $note .= ' '.$validated['admin_note'];
        }
        $record->update(['admin_note' => trim(collect([$record->admin_note, $note])->filter()->implode("\n"))]);

        return back()->with('success', 'Kehadiran manual berhasil dikonfirmasi.');
    }

    private function formatWorkingMinutes(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        return $remainingMinutes > 0 ? $hours.' jam '.$remainingMinutes.' menit' : $hours.' jam';
    }

    public function reviewLeave(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $validated = $request->validate([
            'decision' => ['required', 'in:approved,rejected'],
            'review_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $exceptionService = app(AttendanceLeaveExceptionService::class);
        $exceptionService->expireOverdue();
        $leaveRequest->refresh();

        if ($leaveRequest->attendance_exception && $leaveRequest->status !== 'pending') {
            return back()->with('error', 'Pengajuan izin tetap hadir ini sudah tidak dapat diproses.');
        }

        $leaveRequest->update([
            'status' => $validated['decision'],
            'reviewed_by' => $request->user('admin')->id,
            'reviewed_at' => now(),
            'review_note' => $validated['review_note'] ?? null,
        ]);

        if ($leaveRequest->attendance_exception) {
            if ($validated['decision'] === 'approved') {
                $exceptionService->approve($leaveRequest);
            } else {
                $exceptionService->reject($leaveRequest, $validated['review_note'] ?? null);
            }
        }

        return back()->with('success', $leaveRequest->attendance_exception ? 'Izin tetap hadir dan status scan terkait berhasil diperbarui.' : 'Pengajuan ketidakhadiran berhasil diproses.');
    }
}
