<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class InternshipApplication extends Model
{
    protected $fillable = [
    'application_code',
    'nama',
    'nim',
    'universitas',
    'fakultas',
    'program_studi',
    'semester',
    'email',
    'no_hp',
    'alamat',
    'periode_mulai',
    'periode_selesai',
    'bidang_id',
    'tujuan_magang',
    'status',
    'catatan_admin',
];

    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
    ];

    // Relasi: satu pengajuan punya satu set dokumen
    public function document(): HasOne
    {
        return $this->hasOne(ApplicationDocument::class);
    }

    public function bidang(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(Bidang::class);
}

    // Generate nomor pengajuan otomatis, contoh: MAG20260001
    public static function generateApplicationCode(): string
    {
        $year = Carbon::now()->format('Y');

        $lastCode = self::where('application_code', 'like', "MAG{$year}%")
            ->orderByDesc('id')
            ->value('application_code');

        $lastNumber = $lastCode ? (int) substr($lastCode, -4) : 0;
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "MAG{$year}{$nextNumber}";
    }

    protected static function booted(): void
    {
        static::creating(function (InternshipApplication $application) {
            if (empty($application->application_code)) {
                $application->application_code = self::generateApplicationCode();
            }
        });
    }
}