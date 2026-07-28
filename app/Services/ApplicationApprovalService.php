<?php

namespace App\Services\Admin;

use App\Exceptions\ApplicationAlreadyProcessedException;
use App\Models\Admin;
use App\Models\Application;
use App\Models\Intern;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApplicationApprovalService
{
    /**
     * Terima pengajuan: generate akun mahasiswa (username + password random)
     * lalu simpan ke tabel interns. Dibungkus transaction supaya konsisten.
     *
     * @return array{intern: Intern, plain_password: string}
     */
    public function approve(Application $application, Admin $admin): array
    {
        $this->ensureIsProcessable($application);

        return DB::transaction(function () use ($application, $admin) {
            $username = $this->generateUniqueUsername($application->name);
            $plainPassword = Str::password(10, symbols: false);

            $intern = Intern::create([
                'application_id' => $application->id,
                'department_id' => $application->department_id,
                'name' => $application->name,
                'university' => $application->university,
                'period' => $application->period,
                'status' => Intern::STATUS_AKTIF,
                'username' => $username,
                'password' => Hash::make($plainPassword),
            ]);

            $application->update([
                'status' => Application::STATUS_DITERIMA,
                'processed_by' => $admin->id,
            ]);

            return [
                'intern' => $intern,
                'plain_password' => $plainPassword,
            ];
        });
    }

    /**
     * Tolak pengajuan dengan alasan.
     */
    public function reject(Application $application, Admin $admin, string $reason): Application
    {
        $this->ensureIsProcessable($application);

        $application->update([
            'status' => Application::STATUS_DITOLAK,
            'rejection_reason' => $reason,
            'processed_by' => $admin->id,
        ]);

        return $application->fresh();
    }

    private function ensureIsProcessable(Application $application): void
    {
        if (! in_array($application->status, [Application::STATUS_MENUNGGU, Application::STATUS_DIPROSES], true)) {
            throw new ApplicationAlreadyProcessedException();
        }
    }

    private function generateUniqueUsername(string $name): string
    {
        $base = Str::slug($name, '');
        $username = $base;
        $suffix = 1;

        while (Intern::where('username', $username)->exists()) {
            $username = $base.$suffix;
            $suffix++;
        }

        return $username;
    }
}
