<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Intern;

class InternPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Intern $intern): bool
    {
        return true;
    }

    public function update(Admin $admin, Intern $intern): bool
    {
        return true;
    }

    public function delete(Admin $admin, Intern $intern): bool
    {
        return true;
    }

    public function resetPassword(Admin $admin, Intern $intern): bool
    {
        return $intern->status === Intern::STATUS_AKTIF;
    }

    public function uploadReplyLetter(Admin $admin, Intern $intern): bool
    {
        return $intern->status === Intern::STATUS_AKTIF;
    }
}
