<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Index tambahan berdasarkan pola query yang dipakai di modul Admin:
     * - applications: filter by status, sort by application_date (dashboard & index)
     * - interns: filter by status
     * - departments: filter by is_active (dipakai di dropdown & scope active())
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->index('status');
            $table->index('application_date');
        });

        Schema::table('interns', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['application_date']);
        });

        Schema::table('interns', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });
    }
};
