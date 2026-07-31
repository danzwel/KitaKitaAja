<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Intern;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class InternFactory extends Factory
{
    protected $model = Intern::class;

    public function definition(): array
    {
        return [
            'application_id' => null,
            'department_id' => Department::factory(),
            'name' => fake()->name(),
            'university' => fake()->company().' University',
            'period' => 'Jan 2026 - Mar 2026',
            'status' => Intern::STATUS_AKTIF,
            'username' => fake()->unique()->userName(),
            'password' => Hash::make('password'),
        ];
    }

    public function selesai(): static
    {
        return $this->state(fn () => ['status' => Intern::STATUS_SELESAI]);
    }
}
