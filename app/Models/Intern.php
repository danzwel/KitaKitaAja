<?php

namespace App\Models;

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
    public function department() { return $this->belongsTo(Department::class); }
    public function replyLetters() { return $this->hasMany(ReplyLetter::class); }
    public function attendanceRecords() { return $this->hasMany(AttendanceRecord::class); }
    public function leaveRequests() { return $this->hasMany(LeaveRequest::class); }

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
