<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    public const STATUS_PRESENT = 'hadir';
    public const STATUS_LATE = 'terlambat';
    public const STATUS_PENDING = 'menunggu_verifikasi';
    public const STATUS_LEAVE_PENDING = 'menunggu_persetujuan_izin';
    public const STATUS_ON_TIME = 'tepat_waktu';
    public const STATUS_REJECTED = 'ditolak';

    public const PRESENT_STATUSES = [
        self::STATUS_PRESENT,
        self::STATUS_ON_TIME,
        self::STATUS_LATE,
    ];

    protected $fillable = [
        'intern_id', 'check_in_session_id', 'check_out_session_id', 'check_in_leave_request_id', 'check_out_leave_request_id', 'check_in_confirmed_by', 'check_out_confirmed_by', 'attendance_date',
        'check_in_at', 'check_in_latitude', 'check_in_longitude', 'check_in_distance_meters', 'check_in_status',
        'check_in_confirmed_at', 'check_out_at', 'check_out_latitude', 'check_out_longitude', 'check_out_distance_meters', 'check_out_status', 'check_out_confirmed_at',
        'admin_note',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'check_in_confirmed_at' => 'datetime',
        'check_out_confirmed_at' => 'datetime',
    ];

    public function intern() { return $this->belongsTo(Intern::class); }
    public function checkInSession() { return $this->belongsTo(AttendanceSession::class, 'check_in_session_id'); }
    public function checkOutSession() { return $this->belongsTo(AttendanceSession::class, 'check_out_session_id'); }
    public function checkInLeaveRequest() { return $this->belongsTo(LeaveRequest::class, 'check_in_leave_request_id'); }
    public function checkOutLeaveRequest() { return $this->belongsTo(LeaveRequest::class, 'check_out_leave_request_id'); }
    public function checkInConfirmedBy() { return $this->belongsTo(Admin::class, 'check_in_confirmed_by'); }
    public function checkOutConfirmedBy() { return $this->belongsTo(Admin::class, 'check_out_confirmed_by'); }

    public function getWorkingMinutesAttribute(): int
    {
        if (! $this->check_in_at || ! $this->check_out_at
            || ! in_array($this->check_in_status, self::PRESENT_STATUSES, true)
            || ! in_array($this->check_out_status, self::PRESENT_STATUSES, true)) {
            return 0;
        }

        return max(0, (int) $this->check_in_at->diffInMinutes($this->check_out_at, false));
    }

    public function getWorkingDurationAttribute(): string
    {
        $hours = intdiv($this->working_minutes, 60);
        $minutes = $this->working_minutes % 60;

        return $minutes > 0 ? $hours.' jam '.$minutes.' menit' : $hours.' jam';
    }
}
