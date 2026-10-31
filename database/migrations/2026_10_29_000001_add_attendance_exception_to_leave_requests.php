<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table): void {
            $table->boolean('attendance_exception')->default(false)->after('type');
            $table->time('start_time')->nullable()->after('end_date');
            $table->time('end_time')->nullable()->after('start_time');
            $table->timestamp('approval_deadline_at')->nullable()->after('reviewed_at');
        });

        Schema::table('attendance_records', function (Blueprint $table): void {
            $table->foreignId('check_in_leave_request_id')->nullable()->after('check_in_session_id')
                ->constrained('leave_requests')->nullOnDelete();
            $table->foreignId('check_out_leave_request_id')->nullable()->after('check_out_session_id')
                ->constrained('leave_requests')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table): void {
            $table->dropForeign(['check_in_leave_request_id']);
            $table->dropForeign(['check_out_leave_request_id']);
            $table->dropColumn(['check_in_leave_request_id', 'check_out_leave_request_id']);
        });

        Schema::table('leave_requests', function (Blueprint $table): void {
            $table->dropColumn(['attendance_exception', 'start_time', 'end_time', 'approval_deadline_at']);
        });
    }
};
