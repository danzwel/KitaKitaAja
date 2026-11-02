<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Bidang;
use App\Models\Intern;
use App\Models\InternshipApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_intern_list(): void
    {
        $admin = Admin::factory()->create();
        Intern::factory()->count(3)->create();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.interns.index'))
            ->assertOk();
    }

    public function test_admin_can_update_intern_data(): void
    {
        $admin = Admin::factory()->create();
        $application = InternshipApplication::factory()->diterima()->create();
        $intern = Intern::factory()->create(['internship_application_id' => $application->id]);
        $newBidang = Bidang::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->put(route('admin.interns.update', $intern), [
                'name' => 'Nama Diperbarui',
                'university' => $intern->university,
                'bidang_id' => $newBidang->id,
                'period' => $intern->period,
            ]);

        $response->assertRedirect(route('admin.interns.index'));
        $this->assertDatabaseHas('interns', ['id' => $intern->id, 'name' => 'Nama Diperbarui']);
        $this->assertDatabaseHas('internship_applications', ['id' => $application->id, 'bidang_id' => $newBidang->id]);
    }

    public function test_admin_can_reset_intern_password(): void
    {
        $admin = Admin::factory()->create();
        $intern = Intern::factory()->create();
        $oldPasswordHash = $intern->password;

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.interns.reset-password', $intern))
            ->assertSessionHas('success');

        $this->assertNotEquals($oldPasswordHash, $intern->fresh()->password);
    }

    public function test_reset_password_updates_the_temporary_password_too(): void
    {
        $admin = Admin::factory()->create();
        $intern = Intern::factory()->create([
            'temporary_initial_password' => 'password-lama',
        ]);

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.interns.reset-password', $intern))
            ->assertSessionHas('success');

        $fresh = $intern->fresh();
        $this->assertNotNull($fresh->temporary_initial_password);
        $this->assertTrue(\Hash::check($fresh->temporary_initial_password, $fresh->password));
    }

    public function test_admin_can_delete_intern(): void
    {
        $admin = Admin::factory()->create();
        $intern = Intern::factory()->create();

        $this->actingAs($admin, 'admin')
            ->delete(route('admin.interns.destroy', $intern));

        $this->assertSoftDeleted('interns', ['id' => $intern->id]);
    }
}
