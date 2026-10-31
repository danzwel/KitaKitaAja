<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'intern_id', 'reviewed_by', 'type', 'start_date', 'end_date', 'reason',
        'attendance_exception', 'start_time', 'end_time', 'approval_deadline_at',
        'attachment', 'status', 'review_note', 'reviewed_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'reviewed_at' => 'datetime',
        'approval_deadline_at' => 'datetime',
        'attendance_exception' => 'boolean',
    ];

    public function intern() { return $this->belongsTo(Intern::class); }
    public function reviewer() { return $this->belongsTo(Admin::class, 'reviewed_by'); }

    public function scopeAttendanceException($query)
    {
        return $query->where('attendance_exception', true);
    }
}
