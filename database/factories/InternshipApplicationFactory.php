<?php

namespace Database\Factories;

use App\Models\Bidang;
use App\Models\InternshipApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

class InternshipApplicationFactory extends Factory
{
    protected $model = InternshipApplication::class;

    public function definition(): array
    {
        return [
            'application_code' => 'MAG'.fake()->unique()->numerify('########'),
            'nama' => fake()->name(),
            'nim' => fake()->unique()->numerify('##########'),
            'universitas' => fake()->company().' University',
            'fakultas' => fake()->word().' Fakultas',
            'program_studi' => fake()->jobTitle(),
            'semester' => (string) fake()->numberBetween(1, 8),
            'email' => fake()->safeEmail(),
            'no_hp' => fake()->numerify('08##########'),
            'alamat' => fake()->address(),
            'periode_mulai' => now()->startOfMonth(),
            'periode_selesai' => now()->addMonths(3)->endOfMonth(),
            'bidang_id' => Bidang::factory(),
            'tujuan_magang' => fake()->sentence(),
            'status' => 'menunggu_verifikasi',
        ];
    }

    public function diproses(): static
    {
        return $this->state(fn () => ['status' => 'diproses']);
    }

    public function diterima(): static
    {
        return $this->state(fn () => ['status' => 'diterima']);
    }

    public function ditolak(): static
    {
        return $this->state(fn () => ['status' => 'ditolak']);
    }
}
