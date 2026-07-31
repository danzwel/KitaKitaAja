<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bidang extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_bidang',
        'requires_portfolio',
        'is_active',
    ];

    protected $casts = [
        'requires_portfolio' => 'boolean',
        'is_active' => 'boolean',
    ];
}
