<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Intern;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
