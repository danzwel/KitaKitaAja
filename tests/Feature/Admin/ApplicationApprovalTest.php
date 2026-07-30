<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\InternshipApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_application_list(): void
    {
        $admin = Admin::factory()->create();
        InternshipApplication::factory()->count(3)->create();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.applications.index'))
            ->assertOk();
    }

    public function test_admin_can_search_and_filter_applications(): void
    {
        $admin = Admin::factory()->create();
        InternshipApplication::factory()->create(['nama' => 'Budi Santoso']);
        InternshipApplication::factory()->ditolak()->create(['nama' => 'Ani Wijaya']);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.applications.index', ['q' => 'Budi']))
            ->assertOk()
            ->assertSee('Budi Santoso')
            ->assertDontSee('Ani Wijaya');

        $this->actingAs($admin, 'admin')
            ->get(route('admin.applications.index', ['status' => 'ditolak']))
            ->assertOk()
            ->assertSee('Ani Wijaya')
            ->assertDontSee('Budi Santoso');
    }

    public function test_approving_application_creates_intern_account_and_updates_status(): void
    {
        $admin = Admin::factory()->create();
        $application = InternshipApplication::factory()->create(['nama' => 'Citra Lestari']);

        $response = $this->actingAs($admin, 'admin')
            ->patch(route('admin.applications.approve', $application));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('internship_applications', [
            'id' => $application->id,
            'status' => 'diterima',
        ]);
    }

    public function test_rejecting_application_requires_reason(): void
    {
        $admin = Admin::factory()->create();
        $application = InternshipApplication::factory()->create();

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.applications.reject', $application), [])
            ->assertSessionHasErrors('rejection_reason');
    }

    public function test_rejecting_application_updates_status_and_reason(): void
    {
        $admin = Admin::factory()->create();
        $application = InternshipApplication::factory()->create();

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.applications.reject', $application), [
                'rejection_reason' => 'Kuota bidang magang sudah penuh.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('internship_applications', [
            'id' => $application->id,
            'status' => 'ditolak',
            'catatan_admin' => 'Kuota bidang magang sudah penuh.',
        ]);
    }

    public function test_application_that_is_already_processed_cannot_be_approved_again(): void
    {
        $admin = Admin::factory()->create();
        $application = InternshipApplication::factory()->diterima()->create();

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.applications.approve', $application))
            ->assertRedirect()
            ->assertSessionHas('error');
    }

    public function test_application_that_is_already_processed_cannot_be_rejected_again(): void
    {
        $admin = Admin::factory()->create();
        $application = InternshipApplication::factory()->diterima()->create();

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.applications.reject', $application), [
                'rejection_reason' => 'Tidak dapat diproses ulang.',
            ])
            ->assertRedirect()
            ->assertSessionHas('error');
    }

    public function test_guest_cannot_access_applications(): void
    {
        $application = InternshipApplication::factory()->create();

        $this->get(route('admin.applications.show', $application))
            ->assertRedirect(route('admin.login'));
    }
}
