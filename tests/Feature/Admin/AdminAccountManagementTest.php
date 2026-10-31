<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccountManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_and_manage_another_admin_account(): void
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.accounts.store'), [
                'name' => 'Admin Baru',
                'email' => 'baru@upelkes.test',
                'password' => 'password-baru',
                'password_confirmation' => 'password-baru',
            ])
            ->assertRedirect(route('admin.accounts.index'));

        $account = Admin::where('email', 'baru@upelkes.test')->firstOrFail();
        $this->assertTrue($account->is_active);
        $this->assertTrue(Hash::check('password-baru', $account->password));

        $this->actingAs($admin, 'admin')
            ->put(route('admin.accounts.update', $account), [
                'name' => 'Admin Operasional',
                'email' => 'operasional@upelkes.test',
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.accounts.index'));

        $this->assertDatabaseHas('admins', [
            'id' => $account->id,
            'name' => 'Admin Operasional',
            'email' => 'operasional@upelkes.test',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_disable_another_admin_and_disabled_account_cannot_login(): void
    {
        $admin = Admin::factory()->create();
        $target = Admin::factory()->create(['password' => Hash::make('password-target')]);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.accounts.update', $target), [
                'name' => $target->name,
                'email' => $target->email,
                'is_active' => false,
            ])
            ->assertRedirect(route('admin.accounts.index'));

        $this->assertDatabaseHas('admins', ['id' => $target->id, 'is_active' => false]);

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));

        $this->post(route('admin.login.authenticate'), [
            'email' => $target->email,
            'password' => 'password-target',
        ])->assertSessionHasErrors('email');
    }

    public function test_admin_cannot_disable_the_last_active_admin(): void
    {
        $admin = Admin::factory()->create();
        $target = Admin::factory()->create();
        $admin->update(['is_active' => false]);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.accounts.update', $target), [
                'name' => $target->name,
                'email' => $target->email,
                'is_active' => false,
            ])
            ->assertSessionHas('error');

        $this->assertTrue($target->fresh()->is_active);
    }
}
