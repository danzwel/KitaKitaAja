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
            ['email' => 'admin@uptdpelatihankesehatan.go.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'), // WAJIB diganti sebelum production
                'email_verified_at' => now(),
            ]
        );
    }
}
