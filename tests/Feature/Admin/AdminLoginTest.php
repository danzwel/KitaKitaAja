<?php

namespace Tests\Feature\Admin\Auth;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_can_be_rendered(): void
    {
        $this->get(route('admin.login'))->assertOk();
    }

    public function test_admin_can_login_with_correct_credentials(): void
    {
        $admin = Admin::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($admin, 'admin');
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_cannot_login_with_wrong_password(): void
    {
        $admin = Admin::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest('admin');
        $response->assertSessionHasErrors('email');
    }

    public function test_guest_is_redirected_to_login_when_accessing_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_admin_can_logout(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.logout'));

        $this->assertGuest('admin');
        $response->assertRedirect(route('admin.login'));
    }
}
