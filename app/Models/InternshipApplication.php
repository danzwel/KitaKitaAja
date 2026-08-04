<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class InternshipApplication extends Model
{
    use HasFactory;
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

    public function intern(): HasOne
    {
        return $this->hasOne(Intern::class, 'internship_application_id');
    }

    // Generate nomor pengajuan otomatis, contoh: MAG20260001
    public static function generateApplicationCode(): string
    {
        $year = Carbon::now()->format('Y');

        // Do not rely on the latest row ID. Seeders/imports can insert an older
        // code after a newer one, which would make the old implementation
        // generate an already-existing application code.
        $codes = self::where('application_code', 'like', "MAG{$year}%")
            ->lockForUpdate()
            ->pluck('application_code');

        $lastNumber = $codes->reduce(function (int $highest, string $code): int {
            if (preg_match('/(\d{4})$/', $code, $matches) !== 1) {
                return $highest;
            }

            return max($highest, (int) $matches[1]);
        }, 0);

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
