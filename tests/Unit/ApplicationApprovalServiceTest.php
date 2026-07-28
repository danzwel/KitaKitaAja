<?php

namespace Tests\Unit\Services;

use App\Exceptions\ApplicationAlreadyProcessedException;
use App\Models\Admin;
use App\Models\Application;
use App\Services\Admin\ApplicationApprovalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationApprovalServiceTest extends TestCase
{
    use RefreshDatabase;

    private ApplicationApprovalService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ApplicationApprovalService();
    }

    public function test_approve_generates_unique_username_when_name_collides(): void
    {
        $admin = Admin::factory()->create();

        $firstApplication = Application::factory()->create(['name' => 'Ahmad Fauzi']);
        $result1 = $this->service->approve($firstApplication, $admin);

        $secondApplication = Application::factory()->create(['name' => 'Ahmad Fauzi']);
        $result2 = $this->service->approve($secondApplication, $admin);

        $this->assertNotEquals($result1['intern']->username, $result2['intern']->username);
        $this->assertStringStartsWith('ahmadfauzi', $result2['intern']->username);
    }

    public function test_approve_throws_exception_if_already_processed(): void
    {
        $admin = Admin::factory()->create();
        $application = Application::factory()->diterima()->create();

        $this->expectException(ApplicationAlreadyProcessedException::class);

        $this->service->approve($application, $admin);
    }

    public function test_reject_saves_reason_and_updates_status(): void
    {
        $admin = Admin::factory()->create();
        $application = Application::factory()->create();

        $result = $this->service->reject($application, $admin, 'Dokumen tidak lengkap.');

        $this->assertEquals(Application::STATUS_DITOLAK, $result->status);
        $this->assertEquals('Dokumen tidak lengkap.', $result->rejection_reason);
        $this->assertEquals($admin->id, $result->processed_by);
    }
}
