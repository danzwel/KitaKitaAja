<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Application;

class ApplicationPolicy
{
    /**
     * Semua admin yang login boleh melihat daftar & detail pengajuan.
     */
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Application $application): bool
    {
        return true;
    }

    /**
     * Approve/reject hanya boleh dilakukan jika pengajuan masih
     * berstatus menunggu atau diproses.
     */
    public function process(Admin $admin, Application $application): bool
    {
        return in_array($application->status, [
            Application::STATUS_MENUNGGU,
            Application::STATUS_DIPROSES,
        ], true);
    }
}
