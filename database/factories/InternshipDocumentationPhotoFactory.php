<?php

namespace Database\Factories;

use App\Models\Intern;
use App\Models\InternshipDocumentationPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

class InternshipDocumentationPhotoFactory extends Factory
{
    protected $model = InternshipDocumentationPhoto::class;

    public function definition(): array
    {
        return [
            'year' => now()->year,
            'uploaded_by' => null,
            'intern_id' => Intern::factory()->selesai(),
            'image_path' => 'internship-documentation/'.now()->year.'/'.fake()->uuid().'.jpg',
            'caption' => fake()->sentence(),
        ];
    }
}
