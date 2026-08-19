<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intern_id')->constrained('interns')->cascadeOnDelete();
            $table->foreignId('check_in_session_id')->nullable()->constrained('attendance_sessions')->nullOnDelete();
            $table->foreignId('check_out_session_id')->nullable()->constrained('attendance_sessions')->nullOnDelete();
            $table->date('attendance_date');
            $table->timestamp('check_in_at')->nullable();
            $table->decimal('check_in_latitude', 10, 7)->nullable();
            $table->decimal('check_in_longitude', 10, 7)->nullable();
            $table->unsignedInteger('check_in_distance_meters')->nullable();
            $table->string('check_in_status', 30)->nullable();
            $table->timestamp('check_out_at')->nullable();
            $table->decimal('check_out_latitude', 10, 7)->nullable();
            $table->decimal('check_out_longitude', 10, 7)->nullable();
            $table->unsignedInteger('check_out_distance_meters')->nullable();
            $table->string('check_out_status', 30)->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->unique(['intern_id', 'attendance_date']);
            $table->index(['attendance_date', 'check_in_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
