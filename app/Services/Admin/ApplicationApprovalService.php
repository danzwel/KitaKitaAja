<?php

namespace App\Services\Admin;

use App\Exceptions\ApplicationAlreadyProcessedException;
use App\Models\InternshipApplication;
use Illuminate\Support\Facades\DB;

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

            // TODO: Buat akun Intern setelah model Intern resmi tersedia.
            return $application->fresh();
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
}
