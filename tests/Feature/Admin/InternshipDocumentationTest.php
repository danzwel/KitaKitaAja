<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\InternshipDocumentationPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InternshipDocumentationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_documentation_photo_for_current_year(): void
    {
        Storage::fake('public');
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.documentation.store'), [
                'photo' => UploadedFile::fake()->image('dokumentasi.jpg', 1200, 800),
                'caption' => 'Kegiatan penutupan magang.',
            ])
            ->assertRedirect(route('admin.documentation.index', ['year' => now()->year]));

        $photo = InternshipDocumentationPhoto::firstOrFail();
        $this->assertSame((int) now()->year, $photo->year);
        $this->assertSame($admin->id, $photo->uploaded_by);
        $this->assertSame('Kegiatan penutupan magang.', $photo->caption);
        Storage::disk('public')->assertExists($photo->image_path);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.documentation.index'))
            ->assertOk()
            ->assertSee('Dokumentasi Magang')
            ->assertSee('Kegiatan penutupan magang.');
    }

    public function test_admin_cannot_upload_more_than_ten_documentation_photos_in_one_year(): void
    {
        Storage::fake('public');
        $admin = Admin::factory()->create();

        InternshipDocumentationPhoto::factory()->count(10)->create([
            'year' => now()->year,
            'uploaded_by' => $admin->id,
        ]);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.documentation.store'), [
                'photo' => UploadedFile::fake()->image('foto-ke-sebelas.jpg'),
            ])
            ->assertSessionHas('error', 'Kuota dokumentasi tahun '.now()->year.' sudah mencapai 10 foto.');

        $this->assertSame(10, InternshipDocumentationPhoto::where('year', now()->year)->count());
    }
}
