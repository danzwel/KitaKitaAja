<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Bidang;
use App\Models\InternshipApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_department(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.departments.store'), [
            'nama_bidang' => 'Farmasi Klinik',
            'requires_portfolio' => false,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.departments.index'));
        $this->assertDatabaseHas('bidangs', ['nama_bidang' => 'Farmasi Klinik']);
    }

    public function test_department_name_must_be_unique(): void
    {
        $admin = Admin::factory()->create();
        Bidang::factory()->create(['nama_bidang' => 'Rekam Medis']);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.departments.store'), ['nama_bidang' => 'Rekam Medis'])
            ->assertSessionHasErrors('nama_bidang');
    }

    public function test_admin_can_update_department(): void
    {
        $admin = Admin::factory()->create();
        $department = Bidang::factory()->create(['nama_bidang' => 'Lama']);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.departments.update', $department), [
                'nama_bidang' => 'Baru',
                'requires_portfolio' => true,
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.departments.index'));

        $this->assertDatabaseHas('bidangs', ['id' => $department->id, 'nama_bidang' => 'Baru']);
    }

    public function test_unchecked_active_flag_deactivates_department(): void
    {
        $admin = Admin::factory()->create();
        $department = Bidang::factory()->create(['is_active' => true]);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.departments.update', $department), [
                'nama_bidang' => $department->nama_bidang,
                'requires_portfolio' => $department->requires_portfolio,
            ])
            ->assertRedirect(route('admin.departments.index'));

        $this->assertDatabaseHas('bidangs', [
            'id' => $department->id,
            'is_active' => false,
        ]);
    }

    public function test_admin_can_delete_department_without_relations(): void
    {
        $admin = Admin::factory()->create();
        $department = Bidang::factory()->create();

        $this->actingAs($admin, 'admin')
            ->delete(route('admin.departments.destroy', $department));

        $this->assertDatabaseMissing('bidangs', ['id' => $department->id]);
    }

    public function test_department_with_related_applications_cannot_be_deleted(): void
    {
        $admin = Admin::factory()->create();
        $department = Bidang::factory()->create();
        InternshipApplication::factory()->create(['bidang_id' => $department->id]);

        $response = $this->actingAs($admin, 'admin')
            ->delete(route('admin.departments.destroy', $department));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('bidangs', ['id' => $department->id]);
    }
}
