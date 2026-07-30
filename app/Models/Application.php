<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DIPROSES = 'diproses';
    public const STATUS_DITERIMA = 'diterima';
    public const STATUS_DITOLAK = 'ditolak';

    protected $fillable = ['department_id', 'name', 'nim', 'university', 'major', 'period', 'application_date', 'cover_letter_path', 'cv_path', 'proposal_path', 'status', 'rejection_reason', 'notes', 'processed_by'];

    protected $casts = ['application_date' => 'date'];

    public function department() { return $this->belongsTo(Department::class); }
    public function processedBy() { return $this->belongsTo(Admin::class, 'processed_by'); }
    public function intern() { return $this->hasOne(Intern::class); }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, function (Builder $query) use ($keyword): void {
            $query->where(function (Builder $query) use ($keyword): void {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('nim', 'like', "%{$keyword}%")
                    ->orWhere('university', 'like', "%{$keyword}%");
            });
        });
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return $query->when($status, fn (Builder $query) => $query->where('status', $status));
    }
}
