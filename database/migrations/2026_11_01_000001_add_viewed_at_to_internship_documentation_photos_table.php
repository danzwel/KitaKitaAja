<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internship_documentation_photos', function (Blueprint $table): void {
            $table->timestamp('viewed_at')->nullable()->after('caption');
        });
    }

    public function down(): void
    {
        Schema::table('internship_documentation_photos', function (Blueprint $table): void {
            $table->dropColumn('viewed_at');
        });
    }
};
