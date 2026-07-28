<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function interns(): HasMany
    {
        return $this->hasMany(Intern::class);
    }

    /**
     * Scope untuk bidang yang masih aktif (dipakai di dropdown form, dsb).
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
