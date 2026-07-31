<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDocument extends Model
{
   protected $fillable = [
    'internship_application_id',
    'surat_pengantar',
    'foto',
    'cv',
    'proposal',
    'portofolio',
];

    public function application(): BelongsTo
    {
        return $this->belongsTo(InternshipApplication::class, 'internship_application_id');
    }
}