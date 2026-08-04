<?php

namespace Database\Seeders;

use App\Models\Bidang;
use App\Models\Department;
use App\Models\Intern;
use App\Models\InternshipApplication;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InternSeeder extends Seeder
{
    /**
     * Seeder data mahasiswa magang dummy yang realistis.
     * Membuat data pengajuan (InternshipApplication) + akun mahasiswa (Intern).
     */
    public function run(): void
    {
        // Pastikan bidangs sudah ada
        $bidangs = [
            ['nama_bidang' => 'Rekam Medis', 'requires_portfolio' => false],
            ['nama_bidang' => 'Farmasi', 'requires_portfolio' => false],
            ['nama_bidang' => 'Kesehatan Masyarakat', 'requires_portfolio' => false],
            ['nama_bidang' => 'Teknologi Informasi', 'requires_portfolio' => true],
            ['nama_bidang' => 'Keperawatan', 'requires_portfolio' => false],
        ];
        foreach ($bidangs as $b) {
            Bidang::updateOrCreate(['nama_bidang' => $b['nama_bidang']], $b + ['is_active' => true]);
        }

        // Ambil departments
        $departments = Department::all();
        $bidangAll   = Bidang::all();

        // Data mahasiswa realistis
        $students = [
            [
                'nama'          => 'Raihan Maulana Fadly',
                'nim'           => '2211102441',
                'universitas'   => 'Universitas Jenderal Achmad Yani',
                'fakultas'      => 'Fakultas Teknik',
                'program_studi' => 'Teknik Informatika',
                'semester'      => '6',
                'email'         => 'raihanfadly19@gmail.com',
                'no_hp'         => '081234567890',
                'alamat'        => 'Jl. Terusan Jenderal Sudirman, Cimahi, Jawa Barat',
                'bidang'        => 'Teknologi Informasi',
                'department'    => 'Teknologi Informasi',
            ],
            [
                'nama'          => 'Sofia Risa Aulia',
                'nim'           => '2211102358',
                'universitas'   => 'Universitas Jenderal Achmad Yani',
                'fakultas'      => 'Fakultas Teknik',
                'program_studi' => 'Teknik Informatika',
                'semester'      => '6',
                'email'         => 'sofi.aura@student.unjani.ac.id',
                'no_hp'         => '081298765432',
                'alamat'        => 'Jl. Raya Bandung-Sumedang, Jatinangor',
                'bidang'        => 'Teknologi Informasi',
                'department'    => 'Teknologi Informasi',
            ],
            [
                'nama'          => 'Daniel Desmanto Nugraha',
                'nim'           => '2211102390',
                'universitas'   => 'Universitas Jenderal Achmad Yani',
                'fakultas'      => 'Fakultas Teknik',
                'program_studi' => 'Teknik Informatika',
                'semester'      => '6',
                'email'         => 'daniel.desmanto@student.unjani.ac.id',
                'no_hp'         => '082112345678',
                'alamat'        => 'Jl. Cihampelas No. 100, Bandung',
                'bidang'        => 'Teknologi Informasi',
                'department'    => 'Teknologi Informasi',
            ],
            [
                'nama'          => 'Aisyah Nurhaliza',
                'nim'           => '2311101045',
                'universitas'   => 'Politeknik Piksi Ganesha',
                'fakultas'      => 'Fakultas Kesehatan',
                'program_studi' => 'D3 Rekam Medis',
                'semester'      => '4',
                'email'         => 'aisyah.nurhaliza@piksi.ac.id',
                'no_hp'         => '085712345678',
                'alamat'        => 'Jl. Gatot Subroto No.301, Bandung',
                'bidang'        => 'Rekam Medis',
                'department'    => 'Rekam Medis',
            ],
            [
                'nama'          => 'Muhammad Rizky Fauzan',
                'nim'           => '2111201078',
                'universitas'   => 'Universitas Padjadjaran',
                'fakultas'      => 'Fakultas Farmasi',
                'program_studi' => 'Farmasi',
                'semester'      => '8',
                'email'         => 'rizky.fauzan@unpad.ac.id',
                'no_hp'         => '087812345678',
                'alamat'        => 'Jl. Raya Bandung-Sumedang KM.21, Jatinangor',
                'bidang'        => 'Farmasi',
                'department'    => 'Farmasi',
            ],
            [
                'nama'          => 'Putri Wulandari',
                'nim'           => '2211303112',
                'universitas'   => 'Poltekkes Kemenkes Bandung',
                'fakultas'      => 'Jurusan Keperawatan',
                'program_studi' => 'D3 Keperawatan',
                'semester'      => '5',
                'email'         => 'putri.wulandari@poltekkesbandung.ac.id',
                'no_hp'         => '089612345678',
                'alamat'        => 'Jl. Pajajaran No.56, Bandung',
                'bidang'        => 'Keperawatan',
                'department'    => 'Keperawatan',
            ],
            [
                'nama'          => 'Ahmad Fadilah',
                'nim'           => '2011104090',
                'universitas'   => 'STIKes Dharma Husada Bandung',
                'fakultas'      => 'Fakultas Kesehatan Masyarakat',
                'program_studi' => 'Kesehatan Masyarakat',
                'semester'      => '7',
                'email'         => 'ahmad.fadilah@dhb.ac.id',
                'no_hp'         => '081387654321',
                'alamat'        => 'Jl. Terusan Jakarta No.71-75, Bandung',
                'bidang'        => 'Kesehatan Masyarakat',
                'department'    => 'Kesehatan Masyarakat',
            ],
        ];

        foreach ($students as $data) {
            $bidang     = $bidangAll->firstWhere('nama_bidang', $data['bidang']);
            $department = $departments->firstWhere('name', $data['department']);

            // Buat pengajuan magang (status: diterima)
            $application = InternshipApplication::updateOrCreate(
                ['nim' => $data['nim']],
                [
                    'nama'            => $data['nama'],
                    'universitas'     => $data['universitas'],
                    'fakultas'        => $data['fakultas'],
                    'program_studi'   => $data['program_studi'],
                    'semester'        => $data['semester'],
                    'email'           => $data['email'],
                    'no_hp'           => $data['no_hp'],
                    'alamat'          => $data['alamat'],
                    'periode_mulai'   => '2026-08-01',
                    'periode_selesai' => '2026-12-31',
                    'bidang_id'       => $bidang?->id,
                    'tujuan_magang'   => 'Menerapkan ilmu yang didapat di perkuliahan dalam dunia kerja nyata serta menambah pengalaman profesional di bidang ' . $data['bidang'] . '.',
                    'status'          => 'diterima',
                ]
            );

            // Buat akun intern (password default: password123)
            $initialPassword = 'password123';
            DB::table('interns')->updateOrInsert(
                ['username' => $data['nim']],
                [
                    'internship_application_id' => $application->id,
                    'department_id'             => $department?->id,
                    'name'                      => $data['nama'],
                    'university'                => $data['universitas'],
                    'period'                    => '01 Agustus 2026 - 31 Desember 2026',
                    'status'                    => 'aktif',
                    'password'                  => Hash::make($initialPassword),
                    'temporary_initial_password' => Crypt::encryptString($initialPassword),
                    'email'                     => $data['email'],
                    'phone'                     => $data['no_hp'],
                    'address'                   => $data['alamat'],
                    'updated_at'                => now(),
                    'created_at'                => now(),
                ]
            );
        }
    }
}
