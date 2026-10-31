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

    public function test_outside_radius_attendance_is_rejected(): void
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

        $this->actingAs($intern, 'intern')
            ->post(route('intern.attendance.scan.store', $session), [
                'latitude' => -6.9100000,
                'longitude' => 107.6100000,
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('attendance_records', [
            'intern_id' => $intern->id,
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

    public function test_pending_izin_tetap_hadir_allows_late_check_in_and_is_valid_after_approval(): void
    {
        Carbon::setTestNow('2026-10-29 13:00:00');

        try {
            $intern = Intern::factory()->create();
            $admin = Admin::factory()->create();
            $session = AttendanceSession::create([
                'created_by' => $admin->id,
                'type' => 'datang',
                'attendance_date' => now()->toDateString(),
                'token' => Str::random(48),
                'radius_meters' => 200,
                'is_active' => true,
            ]);
            $leave = LeaveRequest::create([
                'intern_id' => $intern->id,
                'type' => 'izin',
                'attendance_exception' => true,
                'start_date' => now()->toDateString(),
                'end_date' => now()->toDateString(),
                'start_time' => '10:00',
                'end_time' => '12:00',
                'reason' => 'Mengikuti kelas kampus sebelum masuk magang.',
                'status' => 'pending',
                'approval_deadline_at' => now()->addHours(72),
            ]);

            $this->actingAs($intern, 'intern')
                ->post(route('intern.attendance.scan.store', $session))
                ->assertRedirect(route('intern.attendance.index'));

            $this->assertDatabaseHas('attendance_records', [
                'intern_id' => $intern->id,
                'check_in_leave_request_id' => $leave->id,
                'check_in_status' => AttendanceRecord::STATUS_LEAVE_PENDING,
            ]);

            $this->actingAs($admin, 'admin')
                ->patch(route('admin.attendance.leave.review', $leave), ['decision' => 'approved'])
                ->assertRedirect();

            $this->assertDatabaseHas('attendance_records', [
                'intern_id' => $intern->id,
                'check_in_status' => AttendanceRecord::STATUS_ON_TIME,
            ]);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_rejected_izin_tetap_hadir_marks_early_checkout_as_failed(): void
    {
        Carbon::setTestNow('2026-10-29 14:00:00');

        try {
            $intern = Intern::factory()->create();
            $admin = Admin::factory()->create();
            $session = AttendanceSession::create([
                'created_by' => $admin->id,
                'type' => 'pulang',
                'attendance_date' => now()->toDateString(),
                'token' => Str::random(48),
                'radius_meters' => 200,
                'is_active' => true,
            ]);
            $leave = LeaveRequest::create([
                'intern_id' => $intern->id,
                'type' => 'izin',
                'attendance_exception' => true,
                'start_date' => now()->toDateString(),
                'end_date' => now()->toDateString(),
                'start_time' => '14:00',
                'end_time' => '16:00',
                'reason' => 'Mengikuti kelas kampus pada sore hari.',
                'status' => 'pending',
                'approval_deadline_at' => now()->addHours(72),
            ]);
            AttendanceRecord::create([
                'intern_id' => $intern->id,
                'attendance_date' => now()->toDateString(),
                'check_in_at' => now()->setTime(7, 20),
                'check_in_status' => AttendanceRecord::STATUS_ON_TIME,
            ]);

            $this->actingAs($intern, 'intern')
                ->post(route('intern.attendance.scan.store', $session))
                ->assertRedirect(route('intern.attendance.index'));

            $this->actingAs($admin, 'admin')
                ->patch(route('admin.attendance.leave.review', $leave), ['decision' => 'rejected'])
                ->assertRedirect();

            $this->assertDatabaseHas('attendance_records', [
                'intern_id' => $intern->id,
                'check_out_leave_request_id' => $leave->id,
                'check_out_status' => AttendanceRecord::STATUS_REJECTED,
            ]);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_admin_can_download_all_attendance_recap_as_excel(): void
    {
        $admin = Admin::factory()->create();
        Intern::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.attendance.recap.excel'));

        $response->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringStartsWith('PK', $response->streamedContent());
    }

    public function test_admin_can_download_individual_attendance_recap_as_excel(): void
    {
        $admin = Admin::factory()->create();
        $intern = Intern::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.attendance.intern-recap.excel', $intern));

        $response->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringStartsWith('PK', $response->streamedContent());
    }

    public function test_admin_can_filter_and_download_daily_attendance_recap(): void
    {
        $admin = Admin::factory()->create();
        $intern = Intern::factory()->create(['name' => 'Peserta Riwayat Tanggal']);
        $date = now()->subDay()->toDateString();
        AttendanceRecord::create([
            'intern_id' => $intern->id,
            'attendance_date' => $date,
            'check_in_at' => $date.' 07:20:00',
            'check_in_status' => AttendanceRecord::STATUS_ON_TIME,
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.attendance.recap', ['date' => $date]))
            ->assertOk()
            ->assertSee('Peserta Riwayat Tanggal')
            ->assertSee(Carbon::parse($date)->translatedFormat('l, d F Y'));

        $excel = $this->actingAs($admin, 'admin')
            ->get(route('admin.attendance.daily.excel', ['date' => $date]));
        $excel->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringStartsWith('PK', $excel->streamedContent());

        $this->actingAs($admin, 'admin')
            ->get(route('admin.attendance.daily.pdf', ['date' => $date]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_confirm_a_missed_check_in_and_remove_absent_status(): void
    {
        Carbon::setTestNow('2026-10-30 17:00:00');

        try {
            $admin = Admin::factory()->create();
            $intern = Intern::factory()->create();

            $this->actingAs($admin, 'admin')
                ->patch(route('admin.attendance.manual-confirmation', $intern), [
                    'attendance_date' => now()->toDateString(),
                    'confirmation_type' => 'missed_check_in',
                    'admin_note' => 'Peserta dikonfirmasi hadir langsung kepada admin.',
                ])
                ->assertRedirect();

            $record = AttendanceRecord::where('intern_id', $intern->id)->firstOrFail();
            $this->assertSame(AttendanceRecord::STATUS_ON_TIME, $record->check_in_status);
            $this->assertSame('07:30', $record->check_in_at->format('H:i'));
            $this->assertSame($admin->id, $record->check_in_confirmed_by);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_admin_can_correct_late_check_in_and_confirm_missed_check_out_for_working_hours(): void
    {
        Carbon::setTestNow('2026-10-30 17:00:00');

        try {
            $admin = Admin::factory()->create();
            $intern = Intern::factory()->create([
                'created_at' => now()->startOfDay(),
                'updated_at' => now()->startOfDay(),
            ]);
            $record = AttendanceRecord::create([
                'intern_id' => $intern->id,
                'attendance_date' => now()->toDateString(),
                'check_in_at' => now()->setTime(9, 0),
                'check_in_status' => AttendanceRecord::STATUS_LATE,
            ]);

            $this->actingAs($admin, 'admin')
                ->patch(route('admin.attendance.manual-confirmation', $intern), [
                    'attendance_date' => now()->toDateString(),
                    'confirmation_type' => 'correct_late_check_in',
                ])
                ->assertRedirect();

            $this->actingAs($admin, 'admin')
                ->patch(route('admin.attendance.manual-confirmation', $intern), [
                    'attendance_date' => now()->toDateString(),
                    'confirmation_type' => 'missed_check_out',
                ])
                ->assertRedirect();

            $record->refresh();
            $this->assertSame(AttendanceRecord::STATUS_ON_TIME, $record->check_in_status);
            $this->assertSame('07:30', $record->check_in_at->format('H:i'));
            $this->assertSame('16:00', $record->check_out_at->format('H:i'));
            $this->assertSame(510, $record->working_minutes);
            $this->assertSame('8 jam 30 menit', $record->working_duration);

            $view = app(AdminAttendanceController::class)->recap(new Request());
            $summary = $view->getData()['summaryRows']->firstWhere('intern.id', $intern->id);
            $this->assertSame(510, $summary['working_minutes']);
            $this->assertSame('8 jam 30 menit', $summary['working_duration']);
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_admin_can_confirm_when_an_intern_missed_both_check_in_and_check_out(): void
    {
        Carbon::setTestNow('2026-10-30 17:00:00');

        try {
            $admin = Admin::factory()->create();
            $intern = Intern::factory()->create([
                'created_at' => now()->startOfDay(),
                'updated_at' => now()->startOfDay(),
            ]);

            $this->actingAs($admin, 'admin')
                ->patch(route('admin.attendance.manual-confirmation', $intern), [
                    'attendance_date' => now()->toDateString(),
                    'confirmation_type' => 'missed_check_in_and_out',
                ])
                ->assertRedirect();

            $record = AttendanceRecord::where('intern_id', $intern->id)->firstOrFail();
            $this->assertSame('07:30', $record->check_in_at->format('H:i'));
            $this->assertSame('16:00', $record->check_out_at->format('H:i'));
            $this->assertSame(AttendanceRecord::STATUS_ON_TIME, $record->check_in_status);
            $this->assertSame(AttendanceRecord::STATUS_ON_TIME, $record->check_out_status);
            $this->assertSame(510, $record->working_minutes);
        } finally {
            Carbon::setTestNow();
        }
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
