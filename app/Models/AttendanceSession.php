<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by', 'type', 'attendance_date', 'token', 'expires_at',
        'latitude', 'longitude', 'radius_meters', 'is_active',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function admin() { return $this->belongsTo(Admin::class, 'created_by'); }
    public function checkInRecords() { return $this->hasMany(AttendanceRecord::class, 'check_in_session_id'); }
    public function checkOutRecords() { return $this->hasMany(AttendanceRecord::class, 'check_out_session_id'); }

    public function isAvailable(): bool
    {
        return $this->is_active
            && $this->attendance_date->isToday()
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }
}
