<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interns', function (Blueprint $table): void {
            $table->string('final_report_path')->nullable()->after('photo');
            $table->timestamp('final_report_uploaded_at')->nullable()->after('final_report_path');
        });
    }

    public function down(): void
    {
        Schema::table('interns', function (Blueprint $table): void {
            $table->dropColumn(['final_report_path', 'final_report_uploaded_at']);
        });
    }
};
