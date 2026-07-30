<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
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