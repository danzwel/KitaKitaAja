<?php

namespace App\Services\Admin;

use App\Models\Intern;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InternAccountService
{
    /**
     * Reset password mahasiswa ke password random baru.
     *
     * @return string Password baru dalam bentuk plain text (untuk ditampilkan sekali ke admin).
     */
    public function resetPassword(Intern $intern): string
    {
        $newPassword = Str::password(10, symbols: false);

        $intern->update([
            'password' => Hash::make($newPassword),
            'temporary_initial_password' => $newPassword,
        ]);

        return $newPassword;
    }
}
