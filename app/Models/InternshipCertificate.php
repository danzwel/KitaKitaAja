<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipCertificate extends Model
{
    use HasFactory;

    protected $fillable = ['intern_id', 'issued_by', 'certificate_number', 'issued_at'];

    protected $casts = ['issued_at' => 'datetime'];

    public function intern()
    {
        return $this->belongsTo(Intern::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(Admin::class, 'issued_by');
    }
}
