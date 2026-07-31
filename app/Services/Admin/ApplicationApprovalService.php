<?php

namespace App\Services\Admin;

use App\Exceptions\ApplicationAlreadyProcessedException;
use App\Models\Intern;
use App\Models\InternshipApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApplicationApprovalService
{
    /**
     * Terima pengajuan secara atomik.
     */
    public function approve(InternshipApplication $application): InternshipApplication
    {
        return DB::transaction(function () use ($application): InternshipApplication {
            $application = InternshipApplication::query()
                ->lockForUpdate()
                ->findOrFail($application->id);

            $this->ensureIsProcessable($application);

            $application->update([
                'status' => 'diterima',
            ]);

            $intern = Intern::query()
                ->where('internship_application_id', $application->id)
                ->first();

            if (! $intern) {
                $initialPassword = Str::password(random_int(8, 10), symbols: false);

                Intern::create([
                    'internship_application_id' => $application->id,
                    'name' => $application->nama,
                    'university' => $application->universitas,
                    'period' => $application->periode_mulai->format('d M Y').' - '.$application->periode_selesai->format('d M Y'),
                    'status' => Intern::STATUS_AKTIF,
                    'username' => $this->generateUsername($application->nim),
                    'password' => $initialPassword,
                    'temporary_initial_password' => $initialPassword,
                ]);
            }

            return $application->fresh(['intern']);
        });
    }

    /**
     * Tolak pengajuan dengan alasan.
     */
    public function reject(InternshipApplication $application, string $reason): InternshipApplication
    {
        return DB::transaction(function () use ($application, $reason): InternshipApplication {
            $application = InternshipApplication::query()
                ->lockForUpdate()
                ->findOrFail($application->id);

            $this->ensureIsProcessable($application);

            $application->update([
                'status' => 'ditolak',
                'catatan_admin' => $reason,
            ]);

            return $application->fresh();
        });
    }

    private function ensureIsProcessable(InternshipApplication $application): void
    {
        if (! in_array($application->status, ['menunggu_verifikasi', 'diproses'], true)) {
            throw new ApplicationAlreadyProcessedException();
        }
    }

    private function generateUsername(string $nim): string
    {
        $baseUsername = trim($nim);
        $username = $baseUsername;
        $suffix = 0;

        while (Intern::withTrashed()->where('username', $username)->exists()) {
            $suffix++;
            $username = $baseUsername.$suffix;
        }

        return $username;
    }
}
