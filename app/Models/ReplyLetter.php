<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReplyLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'intern_id',
        'uploaded_by',
        'file_path',
    ];

    public function intern(): BelongsTo
    {
        return $this->belongsTo(Intern::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'uploaded_by');
    }
}
