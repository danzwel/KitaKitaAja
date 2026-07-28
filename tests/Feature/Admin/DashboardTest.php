<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Application;
use App\Models\Intern;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_current_statistics(): void
    {
        $admin = Admin::factory()->create();
        Application::factory()->create();
        Application::factory()->diproses()->create();
        Application::factory()->diterima()->create();
        Application::factory()->ditolak()->create();
        Intern::factory()->create(['status' => Intern::STATUS_AKTIF]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Total Pengajuan')
            ->assertSee('Mahasiswa Aktif')
            ->assertSee('Grafik Pengajuan');
    }
}
