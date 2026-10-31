<?php

namespace App\Services;

use App\Models\Intern;

class InternshipStatusService
{
    /**
     * Menyelesaikan magang yang tanggal akhirnya sudah berlalu.
     */
    public function syncExpired(): int
    {
        return Intern::query()
            ->where('status', Intern::STATUS_AKTIF)
            ->whereHas('internshipApplication', function ($query): void {
                $query->whereDate('periode_selesai', '<', now()->toDateString());
            })
            ->update(['status' => Intern::STATUS_SELESAI]);
    }

    public function syncFor(Intern $intern): bool
    {
        $endDate = $intern->internshipApplication?->periode_selesai;
        if ($intern->status !== Intern::STATUS_AKTIF || ! $endDate || ! $endDate->lt(now()->startOfDay())) {
            return false;
        }

        $intern->update(['status' => Intern::STATUS_SELESAI]);

        return true;
    }
}
