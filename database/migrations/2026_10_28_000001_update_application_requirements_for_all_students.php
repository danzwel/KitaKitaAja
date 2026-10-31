<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internship_applications', function (Blueprint $table): void {
            $table->dropForeign(['bidang_id']);
            $table->foreignId('bidang_id')->nullable()->change();
            $table->foreign('bidang_id')->references('id')->on('bidangs');
            $table->string('fakultas')->nullable()->change();
            $table->text('tujuan_magang')->nullable()->change();
        });

        Schema::table('application_documents', function (Blueprint $table): void {
            $table->string('surat_pengantar')->nullable()->change();
            $table->string('transkrip_nilai')->nullable()->after('cv');
        });
    }

    public function down(): void
    {
        Schema::table('application_documents', function (Blueprint $table): void {
            $table->dropColumn('transkrip_nilai');
            $table->string('surat_pengantar')->nullable(false)->change();
        });

        Schema::table('internship_applications', function (Blueprint $table): void {
            $table->dropForeign(['bidang_id']);
            $table->foreignId('bidang_id')->nullable(false)->change();
            $table->foreign('bidang_id')->references('id')->on('bidangs');
            $table->string('fakultas')->nullable(false)->change();
            $table->text('tujuan_magang')->nullable(false)->change();
        });
    }
};
