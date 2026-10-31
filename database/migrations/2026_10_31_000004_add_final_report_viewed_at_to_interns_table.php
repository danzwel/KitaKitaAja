<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interns', function (Blueprint $table): void {
            $table->timestamp('final_report_viewed_at')->nullable()->after('final_report_uploaded_at');
        });
    }

    public function down(): void
    {
        Schema::table('interns', function (Blueprint $table): void {
            $table->dropColumn('final_report_viewed_at');
        });
    }
};
