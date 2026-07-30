<?php

namespace Tests\Unit\Services;

use App\Exceptions\ApplicationAlreadyProcessedException;
use App\Models\InternshipApplication;
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

    public function test_approve_updates_status(): void
    {
        $application = InternshipApplication::factory()->create();

        $result = $this->service->approve($application);

        $this->assertSame('diterima', $result->status);
    }

    public function test_approve_throws_exception_if_already_processed(): void
    {
        $application = InternshipApplication::factory()->diterima()->create();

        $this->expectException(ApplicationAlreadyProcessedException::class);

        $this->service->approve($application);
    }

    public function test_reject_saves_reason_and_updates_status(): void
    {
        $application = InternshipApplication::factory()->create();

        $result = $this->service->reject($application, 'Dokumen tidak lengkap.');

        $this->assertSame('ditolak', $result->status);
        $this->assertSame('Dokumen tidak lengkap.', $result->catatan_admin);
    }
}
