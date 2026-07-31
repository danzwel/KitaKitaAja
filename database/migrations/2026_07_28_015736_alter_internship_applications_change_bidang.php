<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internship_applications', function (Blueprint $table) {
            $table->dropColumn('bidang_diminati');
            $table->foreignId('bidang_id')->after('periode_selesai')->constrained('bidangs');
        });
    }

    public function down(): void
    {
        Schema::table('internship_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('bidang_id');
            $table->string('bidang_diminati')->nullable();
        });
    }
};