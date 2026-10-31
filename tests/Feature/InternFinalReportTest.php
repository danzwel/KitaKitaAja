<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Intern;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InternFinalReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_intern_can_upload_final_report_and_admin_can_view_it(): void
    {
        Storage::fake('public');
        $intern = Intern::factory()->create();
        $admin = Admin::factory()->create();

        $this->actingAs($intern, 'intern')
            ->post(route('intern.reports.store'), [
                'final_report' => UploadedFile::fake()->create('laporan-hasil-magang.pdf', 300, 'application/pdf'),
            ])
            ->assertRedirect(route('intern.reports.index'));

        $intern->refresh();
        $this->assertNotNull($intern->final_report_path);
        $this->assertNotNull($intern->final_report_uploaded_at);
        Storage::disk('public')->assertExists($intern->final_report_path);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.reports.index'))
            ->assertOk()
            ->assertSee('Laporan Hasil Magang')
            ->assertSee($intern->name);

        $this->assertNotNull($intern->fresh()->final_report_viewed_at);
    }

    public function test_new_final_report_replaces_the_previous_file(): void
    {
        Storage::fake('public');
        $intern = Intern::factory()->create();
        $oldPath = 'internship-final-reports/laporan-lama.pdf';
        Storage::disk('public')->put($oldPath, 'laporan lama');
        $intern->update(['final_report_path' => $oldPath]);

        $this->actingAs($intern, 'intern')
            ->post(route('intern.reports.store'), [
                'final_report' => UploadedFile::fake()->create('laporan-baru.pdf', 300, 'application/pdf'),
            ])
            ->assertRedirect(route('intern.reports.index'));

        $intern->refresh();
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($intern->final_report_path);
    }
}
