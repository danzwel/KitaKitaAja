<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Application;
use App\Models\Intern;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_application_list(): void
    {
        $admin = Admin::factory()->create();
        Application::factory()->count(3)->create();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.applications.index'))
            ->assertOk();
    }

    public function test_admin_can_search_and_filter_applications(): void
    {
        $admin = Admin::factory()->create();
        Application::factory()->create(['name' => 'Budi Santoso']);
        Application::factory()->ditolak()->create(['name' => 'Ani Wijaya']);

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
        $application = Application::factory()->create(['name' => 'Citra Lestari']);

        $response = $this->actingAs($admin, 'admin')
            ->patch(route('admin.applications.approve', $application));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => Application::STATUS_DITERIMA,
            'processed_by' => $admin->id,
        ]);

        $this->assertDatabaseHas('interns', [
            'application_id' => $application->id,
            'name' => 'Citra Lestari',
            'status' => Intern::STATUS_AKTIF,
        ]);
    }

    public function test_rejecting_application_requires_reason(): void
    {
        $admin = Admin::factory()->create();
        $application = Application::factory()->create();

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.applications.reject', $application), [])
            ->assertSessionHasErrors('rejection_reason');
    }

    public function test_rejecting_application_updates_status_and_reason(): void
    {
        $admin = Admin::factory()->create();
        $application = Application::factory()->create();

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.applications.reject', $application), [
                'rejection_reason' => 'Kuota bidang magang sudah penuh.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => Application::STATUS_DITOLAK,
            'rejection_reason' => 'Kuota bidang magang sudah penuh.',
        ]);
    }

    public function test_application_that_is_already_processed_cannot_be_approved_again(): void
    {
        $admin = Admin::factory()->create();
        $application = Application::factory()->diterima()->create();

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.applications.approve', $application))
            ->assertForbidden();
    }

    public function test_application_that_is_already_processed_cannot_be_rejected_again(): void
    {
        $admin = Admin::factory()->create();
        $application = Application::factory()->diterima()->create();

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.applications.reject', $application), [
                'rejection_reason' => 'Tidak dapat diproses ulang.',
            ])
            ->assertForbidden();
    }

    public function test_guest_cannot_access_applications(): void
    {
        $application = Application::factory()->create();

        $this->get(route('admin.applications.show', $application))
            ->assertRedirect(route('admin.login'));
    }
}
