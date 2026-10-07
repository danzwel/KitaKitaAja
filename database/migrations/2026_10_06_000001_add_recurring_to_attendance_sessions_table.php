<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table): void {
            $table->boolean('is_recurring')->default(false)->after('attendance_date');
            $table->date('attendance_date')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table): void {
            $table->dropColumn('is_recurring');
            $table->date('attendance_date')->nullable(false)->change();
        });
    }
};
