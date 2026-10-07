<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Intern extends Authenticatable
{
    use HasFactory, SoftDeletes;

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_SELESAI = 'selesai';

    protected $fillable = [
        'application_id',
        'internship_application_id',
        'department_id',
        'name',
        'university',
        'period',
        'status',
        'username',
        'password',
        'temporary_initial_password',
        'photo',
        'email',
        'phone',
        'address',
    ];

    protected $hidden = ['password', 'temporary_initial_password'];

    protected $casts = [
        'password' => 'hashed',
        'temporary_initial_password' => 'encrypted',
    ];

    public function application() { return $this->belongsTo(Application::class); }

    public function internshipApplication()
    {
        return $this->belongsTo(InternshipApplication::class);
    }

    public function getProfilePhotoPathAttribute(): ?string
    {
        return $this->photo ?: $this->internshipApplication?->document?->foto;
    }
    public function department() { return $this->belongsTo(Department::class); }
    public function replyLetters() { return $this->hasMany(ReplyLetter::class); }
    public function attendanceRecords() { return $this->hasMany(AttendanceRecord::class); }
    public function leaveRequests() { return $this->hasMany(LeaveRequest::class); }

    public function approvedLeaveDays(string $type): int
    {
        $today = now()->endOfDay();
        $accountStart = $this->created_at?->copy()->startOfDay();
        $holidays = collect(config('attendance.holidays', []))->filter()->map(fn (string $date): string => Carbon::parse($date)->toDateString())->all();
        $dates = [];

        $this->leaveRequests()
            ->where('type', $type)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today->toDateString())
            ->get()
            ->each(function (LeaveRequest $leave) use (&$dates, $today, $holidays, $accountStart): void {
                $cursor = $leave->start_date->copy();
                if ($accountStart) $cursor = $cursor->max($accountStart);
                $last = $leave->end_date->copy()->min($today);
                while ($cursor->lte($last)) {
                    $date = $cursor->toDateString();
                    if ($cursor->isWeekday() && ! in_array($date, $holidays, true)) {
                        $dates[$date] = true;
                    }
                    $cursor->addDay();
                }
            });

        return count($dates);
    }

    public function alphaDays(): int
    {
        $application = $this->internshipApplication;
        $periodStart = $application?->periode_mulai?->copy()->startOfDay() ?? now()->startOfDay();
        $accountStart = $this->created_at?->copy()->startOfDay() ?? $periodStart;
        $start = $periodStart->max($accountStart);
        $end = $application?->periode_selesai?->copy()->endOfDay() ?? now()->endOfDay();
        $countEnd = $end->copy()->min(now()->endOfDay());
        $alphaEnd = $countEnd->copy()->subDay()->endOfDay();
        if ($start->gt($alphaEnd)) return 0;

        $holidays = collect(config('attendance.holidays', []))->filter()->map(fn (string $date): string => Carbon::parse($date)->toDateString())->all();
        $scheduled = [];
        AttendanceSession::where('type', 'datang')->where(function ($query) use ($start, $countEnd): void {
            $query->where('is_recurring', true)->orWhereBetween('attendance_date', [$start->toDateString(), $countEnd->toDateString()]);
        })->get()->each(function (AttendanceSession $session) use (&$scheduled, $start, $alphaEnd, $holidays): void {
            if ($session->is_recurring) {
                $cursor = $start->copy()->max($session->created_at?->copy()->startOfDay() ?? $start->copy())->startOfDay();
                while ($cursor->lte($alphaEnd)) {
                    if ($cursor->isWeekday() && ! in_array($cursor->toDateString(), $holidays, true)) $scheduled[$cursor->toDateString()] = true;
                    $cursor->addDay();
                }
            } elseif ($session->attendance_date && $session->attendance_date->lte($alphaEnd) && $session->attendance_date->isWeekday() && ! in_array($session->attendance_date->toDateString(), $holidays, true)) {
                $scheduled[$session->attendance_date->toDateString()] = true;
            }
        });

        $present = $this->attendanceRecords()->whereBetween('attendance_date', [$start->toDateString(), $alphaEnd->toDateString()])->whereIn('check_in_status', ['hadir', 'tepat_waktu', 'terlambat'])->pluck('attendance_date')->map(fn ($date): string => Carbon::parse($date)->toDateString())->all();
        $leave = [];
        $this->leaveRequests()->where('status', 'approved')->whereDate('start_date', '<=', $alphaEnd->toDateString())->whereDate('end_date', '>=', $start->toDateString())->get()->each(function (LeaveRequest $request) use (&$leave, $start, $alphaEnd, $holidays): void {
            $cursor = $request->start_date->copy()->max($start);
            $last = $request->end_date->copy()->min($alphaEnd);
            while ($cursor->lte($last)) {
                if ($cursor->isWeekday() && ! in_array($cursor->toDateString(), $holidays, true)) $leave[$cursor->toDateString()] = true;
                $cursor->addDay();
            }
        });

        return count(array_diff_key($scheduled, array_fill_keys([...$present, ...array_keys($leave)], true)));
    }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, function (Builder $query) use ($keyword): void {
            $query->where(function (Builder $query) use ($keyword): void {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('university', 'like', "%{$keyword}%")
                    ->orWhere('username', 'like', "%{$keyword}%");
            });
        });
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return $query->when($status, fn (Builder $query) => $query->where('status', $status));
    }
}
