<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Data awal Bidang Magang. Jalankan: php artisan db:seed --class=DepartmentSeeder
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Rekam Medis', 'description' => 'Pengelolaan data rekam medis pasien.'],
            ['name' => 'Farmasi', 'description' => 'Pengelolaan dan distribusi obat-obatan.'],
            ['name' => 'Keperawatan', 'description' => 'Praktik pelayanan keperawatan dasar.'],
            ['name' => 'Kesehatan Masyarakat', 'description' => 'Program promosi dan edukasi kesehatan.'],
            ['name' => 'Teknologi Informasi', 'description' => 'Pengembangan dan pemeliharaan sistem informasi.'],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['name' => $department['name']],
                [
                    'description' => $department['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
