<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipDocumentationPhoto extends Model
{
    use HasFactory;

    public const MAXIMUM_PHOTOS_PER_YEAR = 10;

    protected $fillable = ['year', 'uploaded_by', 'image_path', 'caption'];

    public function uploadedBy()
    {
        return $this->belongsTo(Admin::class, 'uploaded_by');
    }
}
