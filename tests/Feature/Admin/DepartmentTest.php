<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Application;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_department(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.departments.store'), [
            'name' => 'Farmasi Klinik',
            'description' => 'Bidang farmasi klinik.',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.departments.index'));
        $this->assertDatabaseHas('departments', ['name' => 'Farmasi Klinik']);
    }

    public function test_department_name_must_be_unique(): void
    {
        $admin = Admin::factory()->create();
        Department::factory()->create(['name' => 'Rekam Medis']);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.departments.store'), ['name' => 'Rekam Medis'])
            ->assertSessionHasErrors('name');
    }

    public function test_admin_can_update_department(): void
    {
        $admin = Admin::factory()->create();
        $department = Department::factory()->create(['name' => 'Lama']);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.departments.update', $department), [
                'name' => 'Baru',
                'description' => 'Update deskripsi.',
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.departments.index'));

        $this->assertDatabaseHas('departments', ['id' => $department->id, 'name' => 'Baru']);
    }

    public function test_admin_can_delete_department_without_relations(): void
    {
        $admin = Admin::factory()->create();
        $department = Department::factory()->create();

        $this->actingAs($admin, 'admin')
            ->delete(route('admin.departments.destroy', $department));

        $this->assertSoftDeleted('departments', ['id' => $department->id]);
    }

    public function test_department_with_related_applications_cannot_be_deleted(): void
    {
        $admin = Admin::factory()->create();
        $department = Department::factory()->create();
        Application::factory()->create(['department_id' => $department->id]);

        $response = $this->actingAs($admin, 'admin')
            ->delete(route('admin.departments.destroy', $department));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('departments', ['id' => $department->id, 'deleted_at' => null]);
    }
}
