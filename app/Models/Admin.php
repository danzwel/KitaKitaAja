<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Riwayat pengajuan yang diproses oleh admin ini.
     */
    public function processedApplications(): HasMany
    {
        return $this->hasMany(Application::class, 'processed_by');
    }

    /**
     * Surat balasan yang diunggah oleh admin ini.
     */
    public function uploadedReplyLetters(): HasMany
    {
        return $this->hasMany(ReplyLetter::class, 'uploaded_by');
    }
}
