<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\InternshipDocumentationPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

class InternshipDocumentationPhotoFactory extends Factory
{
    protected $model = InternshipDocumentationPhoto::class;

    public function definition(): array
    {
        return [
            'year' => now()->year,
            'uploaded_by' => Admin::factory(),
            'image_path' => 'internship-documentation/'.now()->year.'/'.fake()->uuid().'.jpg',
            'caption' => fake()->sentence(),
        ];
    }
}
