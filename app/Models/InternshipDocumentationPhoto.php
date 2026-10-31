<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipDocumentationPhoto extends Model
{
    use HasFactory;

    public const MAXIMUM_PHOTOS_PER_INTERN = 5;

    protected $fillable = ['year', 'intern_id', 'image_path', 'caption'];

    public function intern()
    {
        return $this->belongsTo(Intern::class);
    }

    /**
     * Relasi ini hanya dipertahankan untuk menampilkan dokumentasi lama
     * yang dulu diunggah oleh admin.
     */
    public function uploadedBy()
    {
        return $this->belongsTo(Admin::class, 'uploaded_by');
    }
}
