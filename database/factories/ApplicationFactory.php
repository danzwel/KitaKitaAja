<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        return [
            'department_id' => Department::factory(),
            'name' => fake()->name(),
            'nim' => fake()->unique()->numerify('##########'),
            'university' => fake()->company().' University',
            'major' => fake()->jobTitle(),
            'period' => 'Jan 2026 - Mar 2026',
            'application_date' => now(),
            'status' => Application::STATUS_MENUNGGU,
        ];
    }

    public function diproses(): static
    {
        return $this->state(fn () => ['status' => Application::STATUS_DIPROSES]);
    }

    public function diterima(): static
    {
        return $this->state(fn () => ['status' => Application::STATUS_DITERIMA]);
    }

    public function ditolak(): static
    {
        return $this->state(fn () => [
            'status' => Application::STATUS_DITOLAK,
            'rejection_reason' => fake()->sentence(),
        ]);
    }
}
