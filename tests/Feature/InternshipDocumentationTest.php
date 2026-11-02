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

    public function test_completed_intern_can_delete_their_own_documentation_photo(): void
    {
        Storage::fake('public');
        $intern = Intern::factory()->selesai()->create();
        $photo = InternshipDocumentationPhoto::factory()->create([
            'intern_id' => $intern->id,
            'image_path' => 'internship-documentation/foto-salah.jpg',
        ]);
        Storage::disk('public')->put($photo->image_path, 'photo-content');

        $this->actingAs($intern, 'intern')
            ->delete(route('intern.documentation.destroy', $photo))
            ->assertRedirect(route('intern.documentation.index'))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('internship_documentation_photos', ['id' => $photo->id]);
        Storage::disk('public')->assertMissing($photo->image_path);
    }

    public function test_intern_cannot_delete_another_intern_documentation_photo(): void
    {
        $intern = Intern::factory()->selesai()->create();
        $photo = InternshipDocumentationPhoto::factory()->create();

        $this->actingAs($intern, 'intern')
            ->delete(route('intern.documentation.destroy', $photo))
            ->assertForbidden();

        $this->assertDatabaseHas('internship_documentation_photos', ['id' => $photo->id]);
    }
}
