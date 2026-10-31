<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Akun awal untuk instalasi baru. Ubah kredensial ini segera setelah login.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@upelkes.local'],
            [
                'name' => 'Administrator UPTD Pelatihan Kesehatan',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
