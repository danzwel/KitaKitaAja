<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    // Konstanta status, dipakai di controller, view, dan filter
    // agar tidak ada "magic string" yang tersebar di codebase.
    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DIPROSES = 'diproses';
    public const STATUS_DITERIMA = 'diterima';
    public const STATUS_DITOLAK = 'ditolak';

    protected $fillable = [
        'department_id',
        'name',
        'nim',
        'university',
        'major',
        'period',
        'application_date',
        'cover_letter_path',
        'cv_path',
        'proposal_path',
        'status',
        'rejection_reason',
        'notes',
        'processed_by',
    ];

    protected function casts(): array
    {
        return [
            'application_date' => 'date',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }

    public function intern(): HasOne
    {
        return $this->hasOne(Intern::class);
    }

    public function scopeStatus($query, ?string $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopeSearch($query, ?string $keyword)
    {
        return $keyword
            ? $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('nim', 'like', "%{$keyword}%")
                    ->orWhere('university', 'like', "%{$keyword}%");
            })
            : $query;
    }
}
