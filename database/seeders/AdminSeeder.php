<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seeder akun Admin default untuk keperluan development/testing.
     * Jalankan: php artisan db:seed --class=AdminSeeder
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@uptdpelatihankesehatan.go.id')],
            [
                'name' => env('ADMIN_NAME', 'Super Admin'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password123')), // WAJIB diganti sebelum production
                'email_verified_at' => now(),
            ]
        );
    }
}
