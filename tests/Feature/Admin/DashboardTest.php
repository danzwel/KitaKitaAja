<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\InternshipApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_current_statistics(): void
    {
        $admin = Admin::factory()->create();
        InternshipApplication::factory()->create();
        InternshipApplication::factory()->diproses()->create();
        InternshipApplication::factory()->diterima()->create();
        InternshipApplication::factory()->ditolak()->create();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Total Pengajuan')
            ->assertSee('Mahasiswa Aktif')
            ->assertSee('Grafik Pengajuan');
    }
}
