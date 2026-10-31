<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\Intern;
use App\Models\LeaveRequest;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

class AttendanceLeaveExceptionService
{
    public function activeFor(Intern $intern, string $date): ?LeaveRequest
    {
        $this->expireOverdue();

        return $intern->leaveRequests()
            ->attendanceException()
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->where(function ($query): void {
                $query->where('status', 'approved')
                    ->orWhere(function ($query): void {
                        $query->where('status', 'pending')
                            ->where('approval_deadline_at', '>=', now());
                    });
            })
            ->latest()
            ->first();
    }

    public function expireOverdue(): void
    {
        LeaveRequest::query()
            ->attendanceException()
            ->where('status', 'pending')
            ->whereNotNull('approval_deadline_at')
            ->where('approval_deadline_at', '<', now())
            ->get()
            ->each(fn (LeaveRequest $leave) => $this->reject($leave, 'Batas persetujuan admin selama 3×24 jam telah berakhir.'));
    }

    public function approve(LeaveRequest $leave): void
    {
        if (! $leave->attendance_exception) {
            return;
        }

        AttendanceRecord::query()
            ->where('check_in_leave_request_id', $leave->id)
            ->where('check_in_status', AttendanceRecord::STATUS_LEAVE_PENDING)
            ->update(['check_in_status' => AttendanceRecord::STATUS_ON_TIME]);

        AttendanceRecord::query()
            ->where('check_out_leave_request_id', $leave->id)
            ->where('check_out_status', AttendanceRecord::STATUS_LEAVE_PENDING)
            ->update(['check_out_status' => AttendanceRecord::STATUS_ON_TIME]);
    }

    public function reject(LeaveRequest $leave, ?string $note = null): void
    {
        DB::transaction(function () use ($leave, $note): void {
            $leave->update([
                'status' => 'rejected',
                'review_note' => $note ?? $leave->review_note,
            ]);

            AttendanceRecord::query()
                ->where('check_in_leave_request_id', $leave->id)
                ->where('check_in_status', AttendanceRecord::STATUS_LEAVE_PENDING)
                ->update(['check_in_status' => AttendanceRecord::STATUS_REJECTED]);

            AttendanceRecord::query()
                ->where('check_out_leave_request_id', $leave->id)
                ->where('check_out_status', AttendanceRecord::STATUS_LEAVE_PENDING)
                ->update(['check_out_status' => AttendanceRecord::STATUS_REJECTED]);
        });
    }

    public function isPending(LeaveRequest $leave): bool
    {
        return $leave->status === 'pending'
            && $leave->approval_deadline_at instanceof CarbonInterface
            && $leave->approval_deadline_at->isFuture();
    }
}
