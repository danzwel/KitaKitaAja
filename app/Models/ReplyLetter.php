<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplyLetter extends Model
{
    use HasFactory;

    protected $fillable = ['intern_id', 'uploaded_by', 'file_path'];

    public function intern() { return $this->belongsTo(Intern::class); }
    public function uploadedBy() { return $this->belongsTo(Admin::class, 'uploaded_by'); }
}
