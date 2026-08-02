<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function applications() { return $this->hasMany(Application::class); }
    public function interns() { return $this->hasMany(Intern::class); }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
