<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_records', function (Blueprint $table): void {
            $table->foreignId('check_in_confirmed_by')->nullable()->after('check_in_leave_request_id')
                ->constrained('admins')->nullOnDelete();
            $table->timestamp('check_in_confirmed_at')->nullable()->after('check_in_status');
            $table->foreignId('check_out_confirmed_by')->nullable()->after('check_out_leave_request_id')
                ->constrained('admins')->nullOnDelete();
            $table->timestamp('check_out_confirmed_at')->nullable()->after('check_out_status');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table): void {
            $table->dropForeign(['check_in_confirmed_by']);
            $table->dropForeign(['check_out_confirmed_by']);
            $table->dropColumn([
                'check_in_confirmed_by',
                'check_in_confirmed_at',
                'check_out_confirmed_by',
                'check_out_confirmed_at',
            ]);
        });
    }
};
