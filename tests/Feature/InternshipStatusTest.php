<?php

namespace Tests\Feature;

use App\Models\Intern;
use App\Models\InternshipApplication;
use App\Models\AttendanceSession;
use App\Models\Admin;
use App\Services\InternshipStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class InternshipStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_internship_period_is_marked_complete_automatically(): void
    {
        $application = InternshipApplication::factory()->create([
            'periode_mulai' => now()->subMonth()->toDateString(),
            'periode_selesai' => now()->subDay()->toDateString(),
        ]);
        $intern = Intern::factory()->create([
            'internship_application_id' => $application->id,
            'status' => Intern::STATUS_AKTIF,
        ]);

        $updated = app(InternshipStatusService::class)->syncExpired();

        $this->assertSame(1, $updated);
        $this->assertSame(Intern::STATUS_SELESAI, $intern->fresh()->status);
    }

    public function test_internship_is_not_complete_on_its_last_day(): void
    {
        $application = InternshipApplication::factory()->create([
            'periode_mulai' => now()->subMonth()->toDateString(),
            'periode_selesai' => now()->toDateString(),
        ]);
        $intern = Intern::factory()->create([
            'internship_application_id' => $application->id,
            'status' => Intern::STATUS_AKTIF,
        ]);

        app(InternshipStatusService::class)->syncExpired();

        $this->assertSame(Intern::STATUS_AKTIF, $intern->fresh()->status);
    }

    public function test_completed_intern_sees_completion_dashboard_and_cannot_use_attendance_or_leave(): void
    {
        $application = InternshipApplication::factory()->create([
            'periode_mulai' => now()->subMonths(3)->toDateString(),
            'periode_selesai' => now()->subDay()->toDateString(),
        ]);
        $intern = Intern::factory()->create([
            'internship_application_id' => $application->id,
            'status' => Intern::STATUS_AKTIF,
        ]);
        $session = AttendanceSession::create([
            'created_by' => Admin::factory()->create()->id,
            'type' => 'datang',
            'attendance_date' => now()->toDateString(),
            'token' => Str::random(48),
            'radius_meters' => 100,
            'is_active' => true,
        ]);

        $this->actingAs($intern, 'intern')
            ->get(route('intern.dashboard'))
            ->assertOk()
            ->assertSee('Program telah selesai')
            ->assertSee('Menu absensi serta pengajuan izin/sakit telah dinonaktifkan.');

        $this->assertSame(Intern::STATUS_SELESAI, $intern->fresh()->status);

        $this->actingAs($intern, 'intern')
            ->post(route('intern.attendance.scan.store', $session))
            ->assertRedirect(route('intern.dashboard'));

        $this->actingAs($intern, 'intern')
            ->post(route('intern.attendance.leave.store'), [
                'type' => 'sakit',
                'start_date' => now()->toDateString(),
                'end_date' => now()->toDateString(),
                'reason' => 'Pengajuan ini tidak boleh dibuat setelah periode selesai.',
            ])
            ->assertRedirect(route('intern.dashboard'));

        $this->assertDatabaseCount('attendance_records', 0);
        $this->assertDatabaseCount('leave_requests', 0);
    }
}
