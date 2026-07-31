<?php

namespace Database\Factories;

use App\Models\Bidang;
use Illuminate\Database\Eloquent\Factories\Factory;

class BidangFactory extends Factory
{
    protected $model = Bidang::class;

    public function definition(): array
    {
        return [
            'nama_bidang' => fake()->unique()->words(2, true),
            'requires_portfolio' => false,
            'is_active' => true,
        ];
    }
}
