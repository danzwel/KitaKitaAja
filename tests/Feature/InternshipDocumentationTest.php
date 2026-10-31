<?php

namespace Tests\Feature;

use App\Models\Intern;
use App\Models\InternshipDocumentationPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InternshipDocumentationTest extends TestCase
{
    use RefreshDatabase;

    public function test_completed_intern_can_upload_up_to_five_documentation_photos(): void
    {
        Storage::fake('public');
        $intern = Intern::factory()->selesai()->create();

        $this->actingAs($intern, 'intern')
            ->post(route('intern.documentation.store'), [
                'photos' => [
                    UploadedFile::fake()->image('satu.jpg'),
                    UploadedFile::fake()->image('dua.jpg'),
                ],
                'caption' => 'Kegiatan penutupan magang.',
            ])
            ->assertRedirect(route('intern.documentation.index'));

        $this->assertSame(2, $intern->documentationPhotos()->count());
        $intern->documentationPhotos()->each(function (InternshipDocumentationPhoto $photo): void {
            $this->assertSame('Kegiatan penutupan magang.', $photo->caption);
            Storage::disk('public')->assertExists($photo->image_path);
        });
    }

    public function test_active_intern_cannot_open_or_upload_documentation(): void
    {
        $intern = Intern::factory()->create();

        $this->actingAs($intern, 'intern')
            ->get(route('intern.documentation.index'))
            ->assertRedirect(route('intern.dashboard'));

        $this->actingAs($intern, 'intern')
            ->post(route('intern.documentation.store'), [
                'photos' => [UploadedFile::fake()->image('foto.jpg')],
            ])
            ->assertRedirect(route('intern.dashboard'));

        $this->assertDatabaseCount('internship_documentation_photos', 0);
    }

    public function test_intern_cannot_upload_more_than_five_documentation_photos(): void
    {
        Storage::fake('public');
        $intern = Intern::factory()->selesai()->create();
        InternshipDocumentationPhoto::factory()->count(4)->create(['intern_id' => $intern->id]);

        $this->actingAs($intern, 'intern')
            ->post(route('intern.documentation.store'), [
                'photos' => [
                    UploadedFile::fake()->image('lima.jpg'),
                    UploadedFile::fake()->image('enam.jpg'),
                ],
            ])
            ->assertSessionHas('error', 'Anda hanya dapat mengunggah 1 foto lagi. Batas dokumentasi adalah 5 foto per mahasiswa.');

        $this->assertSame(4, $intern->documentationPhotos()->count());
    }
}
