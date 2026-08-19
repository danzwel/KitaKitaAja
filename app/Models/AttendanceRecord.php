<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    public const STATUS_PRESENT = 'hadir';
    public const STATUS_LATE = 'terlambat';
    public const STATUS_PENDING = 'menunggu_verifikasi';
    public const STATUS_REJECTED = 'ditolak';

    protected $fillable = [
        'intern_id', 'check_in_session_id', 'check_out_session_id', 'attendance_date',
        'check_in_at', 'check_in_latitude', 'check_in_longitude', 'check_in_distance_meters', 'check_in_status',
        'check_out_at', 'check_out_latitude', 'check_out_longitude', 'check_out_distance_meters', 'check_out_status',
        'admin_note',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
    ];

    public function intern() { return $this->belongsTo(Intern::class); }
    public function checkInSession() { return $this->belongsTo(AttendanceSession::class, 'check_in_session_id'); }
    public function checkOutSession() { return $this->belongsTo(AttendanceSession::class, 'check_out_session_id'); }
}
