<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Intern;
use App\Models\InternshipApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InternAuthenticationTest extends TestCase
{
    public function test_guest_can_open_the_intern_login_page(): void
    {
        $this->get(route('intern.login'))
            ->assertOk()
            ->assertViewIs('intern.auth.login');
    }

    use RefreshDatabase;

    public function test_guest_is_redirected_to_intern_login_for_intern_pages(): void
    {
        $this->get(route('intern.dashboard'))
            ->assertRedirect(route('intern.login'));
    }

    public function test_accepted_application_shows_intern_credentials_on_status_page(): void
    {
        $application = InternshipApplication::factory()->diterima()->create();
        $password = 'Ab12CD89';

        Intern::factory()->create([
            'internship_application_id' => $application->id,
            'username' => $application->nim,
            'password' => Hash::make($password),
            'temporary_initial_password' => $password,
        ]);

        $this->post(route('cek-status.result'), [
            'application_code' => $application->application_code,
            'email' => $application->email,
        ])
            ->assertOk()
            ->assertSee($application->nim)
            ->assertSee($password)
            ->assertSee('Gunakan akun berikut untuk login ke Dashboard Mahasiswa.');
    }

    public function test_non_accepted_application_does_not_show_intern_credentials(): void
    {
        $application = InternshipApplication::factory()->create();

        $this->post(route('cek-status.result'), [
            'application_code' => $application->application_code,
            'email' => $application->email,
        ])
            ->assertOk()
            ->assertDontSee('Username:')
            ->assertDontSee('Password awal:');
    }

    public function test_intern_can_login_with_generated_credentials(): void
    {
        $password = 'Ab12CD89';
        $intern = Intern::factory()->create([
            'username' => 'mahasiswa-login',
            'password' => Hash::make($password),
        ]);

        $this->post(route('intern.login'), [
            'username' => $intern->username,
            'password' => $password,
        ])
            ->assertRedirect(route('intern.dashboard'));

        $this->assertAuthenticatedAs($intern, 'intern');
    }
}
