<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Intern;
use App\Models\InternshipDocumentationPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternshipDocumentationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_student_documentation_but_has_no_upload_or_delete_actions(): void
    {
        $admin = Admin::factory()->create();
        $intern = Intern::factory()->selesai()->create();
        InternshipDocumentationPhoto::factory()->create([
            'intern_id' => $intern->id,
            'caption' => 'Kegiatan penutupan magang.',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.documentation.index'))
            ->assertOk()
            ->assertSee('Dokumentasi Magang')
            ->assertSee('Kegiatan penutupan magang.')
            ->assertSee($intern->name)
            ->assertDontSee('Unggah Foto Dokumentasi')
            ->assertDontSee('ti-trash');

        $this->assertNotNull(InternshipDocumentationPhoto::first()->viewed_at);

        $this->actingAs($admin, 'admin')
            ->post('/admin/documentation')
            ->assertNotFound();
    }
}
