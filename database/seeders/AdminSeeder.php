<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seeder akun Admin menggunakan data real/contoh pegawai.
     * Jalankan: php artisan db:seed --class=AdminSeeder
     */
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Drg. Hj. Rini Kartikawati, M.Kes',
                'email' => 'rini.kartikawati@uptdpelatihankesehatan.go.id',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Asep Sutisna, S.Kep., Ners',
                'email' => 'asep.sutisna@uptdpelatihankesehatan.go.id',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Dra. Nengsih Haryati',
                'email' => 'nengsih.haryati@uptdpelatihankesehatan.go.id',
                'password' => Hash::make('password123'),
            ]
        ];

        foreach ($admins as $admin) {
            Admin::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => $admin['password'],
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
