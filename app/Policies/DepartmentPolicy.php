<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Department;

class DepartmentPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return true;
    }

    public function update(Admin $admin, Department $department): bool
    {
        return true;
    }

    /**
     * Semua admin boleh mencoba menghapus; validasi "masih dipakai atau
     * tidak" adalah aturan bisnis, ditangani terpisah di controller supaya
     * bisa memberi flash message yang ramah, bukan halaman 403 mentah.
     */
    public function delete(Admin $admin, Department $department): bool
    {
        return true;
    }
}
