<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Intern;
use App\Models\InternshipApplication;
use App\Models\LeaveRequest;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_intern_login_for_attendance(): void
    {
        $this->get(route('intern.attendance.index'))
            ->assertRedirect(route('intern.login'));
    }

    public function test_intern_can_submit_attendance_and_location_is_saved(): void
    {
        $intern = Intern::factory()->create();
        $session = AttendanceSession::create([
            'created_by' => Admin::factory()->create()->id,
            'type' => 'datang',
            'attendance_date' => now()->toDateString(),
            'token' => Str::random(48),
            'latitude' => -6.9000000,
            'longitude' => 107.6000000,
            'radius_meters' => 200,
            'is_active' => true,
        ]);

        $this->actingAs($intern, 'intern')
            ->post(route('intern.attendance.scan.store', $session), [
                'latitude' => -6.9001000,
                'longitude' => 107.6001000,
            ])
            ->assertRedirect(route('intern.attendance.index'));

        $record = AttendanceRecord::where('intern_id', $intern->id)->firstOrFail();
        $this->assertNotNull($record->check_in_at);
        $this->assertSame(-6.9001, (float) $record->check_in_latitude);
        $this->assertContains($record->check_in_status, ['tepat_waktu', 'terlambat', 'menunggu_verifikasi']);
    }

    public function test_outside_radius_attendance_waits_for_admin_review(): void
    {
        $intern = Intern::factory()->create();
        $session = AttendanceSession::create([
            'created_by' => Admin::factory()->create()->id,
            'type' => 'datang',
            'attendance_date' => now()->toDateString(),
            'token' => Str::random(48),
            'latitude' => -6.9000000,
            'longitude' => 107.6000000,
            'radius_meters' => 50,
            'is_active' => true,
        ]);

        $this->actingAs($intern, 'intern')->post(route('intern.attendance.scan.store', $session), [
            'latitude' => -6.9100000,
            'longitude' => 107.6100000,
        ]);

        $this->assertDatabaseHas('attendance_records', [
            'intern_id' => $intern->id,
            'check_in_status' => AttendanceRecord::STATUS_PENDING,
        ]);
    }

    public function test_intern_can_submit_leave_request(): void
    {
        $intern = Intern::factory()->create();

        $this->actingAs($intern, 'intern')
            ->post(route('intern.attendance.leave.store'), [
                'type' => 'sakit',
                'start_date' => now()->toDateString(),
                'end_date' => now()->toDateString(),
                'reason' => 'Demam dan perlu beristirahat di rumah.',
            ])
            ->assertRedirect(route('intern.attendance.leave'));

        $this->assertDatabaseHas('leave_requests', [
            'intern_id' => $intern->id,
            'type' => 'sakit',
            'status' => 'pending',
        ]);
    }

    public function test_recap_only_counts_days_that_have_a_schedule_or_attendance_status(): void
    {
        Carbon::setTestNow('2026-10-26 09:00:00');

        try {
            $application = InternshipApplication::factory()->create([
                'periode_mulai' => '2026-07-13',
                'periode_selesai' => '2026-10-16',
            ]);
            $intern = Intern::factory()->create([
                'internship_application_id' => $application->id,
                'created_at' => '2026-09-24 08:39:28',
                'updated_at' => '2026-09-24 08:39:28',
            ]);
            $admin = Admin::factory()->create();

            AttendanceSession::create([
                'created_by' => $admin->id,
                'type' => 'datang',
                'attendance_date' => '2026-09-28',
                'token' => Str::random(48),
                'radius_meters' => 200,
                'is_active' => true,
            ]);
            $recurringSession = AttendanceSession::create([
                'created_by' => $admin->id,
                'type' => 'datang',
                'is_recurring' => true,
                'token' => Str::random(48),
                'radius_meters' => 200,
                'is_active' => true,
            ]);
            $recurringSession->forceFill([
                'created_at' => '2026-10-06 14:22:18',
                'updated_at' => '2026-10-06 14:22:18',
            ])->saveQuietly();

            foreach (['2026-09-28', '2026-10-06', '2026-10-07'] as $date) {
                AttendanceRecord::create([
                    'intern_id' => $intern->id,
                    'attendance_date' => $date,
                    'check_in_status' => 'terlambat',
                ]);
            }
            LeaveRequest::create([
                'intern_id' => $intern->id,
                'type' => 'sakit',
                'start_date' => '2026-10-08',
                'end_date' => '2026-10-13',
                'reason' => 'Sakit.',
                'status' => 'approved',
            ]);
            LeaveRequest::create([
                'intern_id' => $intern->id,
                'type' => 'izin',
                'start_date' => '2026-10-15',
                'end_date' => '2026-10-15',
                'reason' => 'Izin.',
                'status' => 'approved',
            ]);

            $view = app(AdminAttendanceController::class)->recap(new Request());
            $row = $view->getData()['summaryRows']->first();

            $this->assertSame(10, $row['working_days']);
            $this->assertSame(3, $row['present']);
            $this->assertSame(3, $row['late']);
            $this->assertSame(4, $row['sakit']);
            $this->assertSame(1, $row['izin']);
            $this->assertSame(2, $row['alpha']);
            $this->assertSame(2, $intern->alphaDays());
        } finally {
            Carbon::setTestNow();
        }
    }
}
