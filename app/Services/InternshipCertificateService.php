<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Intern;
use App\Models\InternshipCertificate;
use Illuminate\Support\Facades\DB;

class InternshipCertificateService
{
    public function issue(Intern $intern, Admin $admin): InternshipCertificate
    {
        return DB::transaction(function () use ($intern, $admin): InternshipCertificate {
            $existing = $intern->certificate;
            if ($existing) {
                return $existing;
            }

            $year = now()->year;
            $sequence = InternshipCertificate::query()
                ->whereYear('issued_at', $year)
                ->lockForUpdate()
                ->count() + 1;

            return InternshipCertificate::create([
                'intern_id' => $intern->id,
                'issued_by' => $admin->id,
                'certificate_number' => sprintf('UPLK/SM/%d/%04d', $year, $sequence),
                'issued_at' => now(),
            ]);
        });
    }
}
